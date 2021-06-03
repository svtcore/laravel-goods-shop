<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\PaymentType;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Getting all orders
     * return view with data
     */
    public function index()
    {
        $data = Order::getAllOrdersData()->get();
        if (count($data) > 0)
            return view('admin.orders')->with('orders', Order::getAllOrdersData()->paginate(15));
        else return view('admin.orders')->with('orders', null);
    }

    /**
     * Getting data order by id
     * if doesnt exist return 404 page
     */
    public function show($id)
    {
        $order = Order::getById($id);
        if (!empty($order))
                return view('admin.order_show')->with('order', $order);
        else return abort(404);
    }

    /**
     * Getting data order and return with view
     * if doesnt exist return 404 page
     */
    public function edit($id)
    {
        $order = Order::getById($id);
        if (!empty($order)){
            return view('admin.order_edit')
                    ->with('order', $order)
                    ->with('payment', $this->getavailablePayments());
        }
        else abort(404);
    }


     /**
     * Update orders status by id
     */
    private function change_status($id, $status){
        try{
            Order::updateStatus($id, $status, null);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Update data with custom validate function
     */
    public function update(Request $request, $id)
    {
        if (isset($request->type)){
            if ($request->type == "confirm") {
                $this->change_status($id, "processing");
                return redirect()->back();
            }
            elseif ($request->type == "cancel"){
                $this->change_status($id, "canceled");
                return redirect()->back();
            }
            elseif ($request->type == "complete"){
                $this->change_status($id, "completed");
                return redirect()->back();
            }
            elseif ($request->type == "created"){
                $this->change_status($id, "created");
                return redirect()->back();
            }
            elseif ($request->type == "edit_data"){
                $validation_rules = $this->validate_rules($id, $request);
                $validated = $request->validate($validation_rules);
                $this->update_order_data($id, $validated);
                return redirect()->route('admin.orders');
            }
        }
    }
    /**
     * Deleting orders (soft delete)
     */
    public function destroy($id)
    {   try{
            Order::findOrFail($id)->order_products()->delete();
            Order::findOrFail($id)->delete();
            return redirect()->back();
        }
        catch(Exception $e){
            return redirect()->back();
        }
    }

    /**
     * Validate rules data through method because some of orders have unknown id
     * so request validate form doesnt correct for this task
     */
    public function validate_rules($id){
        try{
            $validation_array = array();
            $data = OrderProduct::getByOrderId($id);
            foreach($data as $product)
                    $validation_array["product_count_".$product->f_product_id] = "required|numeric|min:0";
                $validation_array['f_name'] = 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50';
                $validation_array['l_name'] = 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50';
                $validation_array['phone'] = 'required|digits_between:10,25';
                $validation_array['street'] = 'required|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:2|max:100';
                $validation_array['house'] = 'required|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:1|max:5';
                $validation_array['appart'] = 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.,-]+$/i|min:1|max:5';
                $validation_array['entrance'] = 'nullable|digits_between:1,5';
                $validation_array['code'] = 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№,-]+$/i|min:1|max:10';
                $validation_array['payment'] = 'required|digits_between:1,100|min:1|max:3';
                $validation_array['note'] = 'nullable|regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№%()?!:;=,-]+$/i|min:1|max:255';
                $validation_array['status'] = 'required|regex:/^[a-zа-яÀ-ÿẞ\s.,-]+$/i|min:2|max:50';
            return $validation_array;
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Formation data, caclulate total price and update order data
     */
    public function updateOrder($validated, $id){
        try
        {
            $sum = OrderProduct::getSumProductsByOrderId($id);
            if ($sum > 0){
                $data = [
                    'order_fname' => $validated['f_name'],
                    'order_lname' => $validated['l_name'],
                    'order_phone' => $validated['phone'],
                    'f_pay_t_id' => $validated['payment'],
                    'order_full_price' => $sum, 
                    'order_note' => $validated['note'], 
                    'order_code' => $validated['code'],
                    'order_status' => $validated['status']
                ];
                Order::where('order_id', $id)->update($data);
                $this->updateOrderAddress($validated, $id);
            }
            else{
                $this->change_status($id, "canceled");
            }
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['message' => 'Failed to update order']);
        }

    }

    /**
     * Formation data, update order address data
     */

    public function updateOrderAddress($validated, $id){
        try
        {
            $data = [
                'user_str_name' => $validated['street'],
                'user_house_num' => $validated['house'],
                'user_ent_num' => $validated['entrance'],
                'user_apart_num'=> $validated['appart'],
                'user_code' => $validated['code']
            ];
            $order = Order::findOrFail($id)->user_addresses()->update($data);
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['message' => 'Failed to update order address']);
        }
    }

    /**
     * Validate data and then divided input product param name to get product id
     * if count of product equals 0 then delete it from order
     */
    public function update_order_data($id, $validated){
        try{
            foreach ($validated as $key => $value){
                if (str_starts_with($key, 'product_count'))
                {
                    $apart = explode("_", $key);
                    $product_id = $apart[2];
                    if ($value != 0)
                    {
                        $product_data = Product::getProductPrice($product_id);
                        $new_price = $product_data->product_price * $value;
                        OrderProduct::updateCountPrice($product_id, $value, $new_price);
                    }
                    elseif ($value == 0)
                    {
                        OrderProduct::deleteByProductId($product_id);
                    }
                }
            }
            $this->updateOrder($validated, $id);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Get available payment types and format it to array
     * so that display it in view like droplist
     */
    public function getavailablePayments(){
        try{
            $payment = PaymentType::getAvailable();
            $payment_array = array();
            foreach($payment as $pay){
                $payment_array[$pay->pay_t_id] = $pay->pay_t_name;
            }
            return $payment_array;
        }
        catch(Exception $e){
            return array(null);
        }
    }

    /**
     * Validate data and run search function
     * retunr object with data
     */
    public function search(Request $request){
        try{
            $validated = $request->validate([
                'query' => 'regex:/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№%?!:;,-]+$/i|min:1|max:255',
            ]);
            $results = Order::search($validated['query']);
            return $results;
        }
        catch(Exception $e){
            return 0;
        }
    }
}
