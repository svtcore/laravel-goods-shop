<?php

namespace App\Http\Controllers\user;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Category;
use App\Models\UserAddress;
use App\Models\PaymentType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{

    public $categories;

    /**
     * Load category list to navbar
     */
    public function __construct()
    {
        $this->categories = Category::All();
    }
    /**
     * Get user orders
     * if not authorized redirect to login page
     */
    public function index()
    {
        if (Auth::check())
            if (!empty($orders = Order::getByUserId(Auth::id())->get())){
                if (count($orders) > 0)
                    return view('user.orders')->with('orders', Order::getByUserId(Auth::id())->paginate(15))->with('categories', $this->categories);
                else return view('user.orders')->with('orders', null)->with('categories', $this->categories);
            }

        else return redirect()->route('login')->with('categories', $this->categories);
    }

    /**
     * Check if exist cookies with order list
     * Run function to formation cookies to array and create record
     * Return order data, payment type list and category list for nav bar
     */
    public function create(Request $request) 
    {
        if (isset($_COOKIE['user_cart']))
        {
            $cookies = $_COOKIE['user_cart'];
            $data_arr = $this->formatCookies($cookies);
            if ($data_arr != null){
                return view('user.cart')->with('order_products', $data_arr[1])
                    ->with('total', $data_arr[0])->with('payment', $this->getavailablePayments())
                    ->with('categories', $this->categories);
            }else{
                return redirect()->route('user.order.cart')->withCookie(Cookie::forget('user_cart'));
            }
        }
        else{
            return view('user.cart')->with('order_products', null)->with('categories', $this->categories);;
        }
    }

    /**
     * Validate date through Request\StoreOrderRequest
     * Formation order cookies to order array
     * Check if user authorized then link this order to exist user
     * else just add order and create new user
     */
    public function store(StoreOrderRequest $request)
    {
        try
        {
            if (isset($request['submit'])){
                $password = null;
                $order_array = $this->formatStoreCookies($request->validated());
                if ($order_array != null){
                    if (Auth::check()) $id = Auth::id();
                    else {
                        $user_data_arr = $this->addNewUser($request);
                        $password = $user_data_arr[1];
                        if ($user_data_arr[1] != null)
                            $id = $user_data_arr[0]->user_id;
                        else $id = $user_data_arr[0];
                    }
                    $id = $this->addNewOrder($request, $id, $order_array);    
                    return view('user.order_complete')
                            ->with('order_id', $id)
                            ->with('password', $password)
                            ->with('categories', $this->categories);
                }
                else return redirect()->route('user.order.cart');
            }
        }
        catch(Exception $e){
            return redirect()->route('user.order.cart');
        }
    }

    /**
     * Get available payment types convert to array
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
     * Subfunction to formation product name by user language
     */
    public function getCurrentProductLangName($data){
        try{
            $lang = app()->getLocale();
            if ($lang == "en") $name = $data->names->product_name_lang_en;
            elseif($lang == "de") $name = $data->names->product_name_lang_de;
            elseif($lang == "uk") $name = $data->names->product_name_lang_uk;
            elseif($lang == "ru") $name = $data->names->product_name_lang_ru;
            else $name = $data->product_name_lang_en;
            return $name;
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Formation cookies to array for store order
     * cookies have 2 values 
     * id  - product id
     * count - count of selected product
     */
    public function formatStoreCookies($request){
        try{
            if (isset($_COOKIE['user_cart']))
            {
                $order_array = json_decode($_COOKIE['user_cart']);
                for ($i = 0; $i < count($order_array->items);$i++)
                {
                    $id = preg_replace( '/[^0-9]/', '', $order_array->items[$i]->id);
                    $count = preg_replace( '/[^0-9]/', '', $order_array->items[$i]->count);
                    if (!empty($id) && !empty($count)){
                        $order_array->items[$i]->id = $id;
                        $order_array->items[$i]->count = $count;
                    }
                }
                return $order_array;
            }
            else return redirect()->route('user.cart');
        }
        catch(Exception $e){
            return redirect()->route('user.cart');
        }
    }

    /**
     * Formation cookies to array when get cart page
     */
    public function formatCookies($cookies){
        try{
            $order_array = json_decode($cookies);
            $order_data = array();
            $total_products_price = 0;
            for ($i = 0; $i < count($order_array->items);$i++)
            {
                $id = preg_replace( '/[^0-9]+/', '', $order_array->items[$i]->id);
                $count = preg_replace( '/[^0-9]+/', '', $order_array->items[$i]->count);
                $data = Product::getById($id);
                if (!empty($data))
                {
                    $order_data[$i]['id'] = $data->product_id;
                    $order_data[$i]['name'] = $this->getCurrentProductLangName($data);
                    $order_data[$i]['price'] = $data->product_price;
                    $order_data[$i]['image'] = $data->images[0]['product_image_name'];
                    $order_data[$i]['count'] = $count;
                    $order_data[$i]['one_price'] = $data->product_price;
                    $order_data[$i]['total_product_price'] = $data->product_price * $count;
                    $total_products_price += $order_data[$i]['total_product_price'];
                }
                else return null;
            }
            $total = $total_products_price;
            return array($total, $order_data);
        }
        catch(Exception $e){
            return redirect()->route('user.cart');
        }
    }
    /**
     * Generate user password
     */
    public function generateRandomPassword() {
        try{
            $password = '';
            $desired_length = rand(8, 12);
            for($length = 0; $length < $desired_length; $length++) {
            $password .= chr(rand(32, 126));
            }
            return $password;
        }
        catch(Exception $e){
            return redirect()->route('user.cart');
        }
    }

    /**
     * if user phone exist then return user id
     * else cretae new user
     * return user id
     */
    public function addNewUser($request){
        try{
            $user_data = User::getByPhone($request->phone);
            if (isset($user_data->user_id))
                return array($user_data->user_id, null);
            else
            {
                $password = $this->generateRandomPassword();
                $data = [
                    'user_fname' => $request->f_name,
                    'user_lname' => $request->l_name,
                    'user_phone' => $request->phone,
                    'password' => bcrypt($password)
                ];
                return array(User::create($data), $password);
            }
        }
        catch(Exception $e){
            return redirect()->route('user.cart');
        }
    }

    /**
     * Adding order data to db
     * with function to add data to order_products table
     */
    public function addNewOrder($request, $id, $order_array){
        try{
            $order_data = [
                'f_user_id' => $id,
                'f_pay_t_id' => $request->payment,
                'order_fname' => $request->f_name,
                'order_lname' => $request->l_name,
                'order_phone' => $request->phone,
                'order_note' => $request->note,
                'order_code' => $request->code,
                'order_status' => 'created'
                ];
                $order = Order::create($order_data);
                $this->addNewUserAddress($order, $request);
                $price = $this->addNewOrderProduct($order, $order_array);
                Order::updatePrice($order->order_id, $price);
                return $order->order_id;
        }
        catch(Exception $e){
            return redirect()->route('user.cart');
        }
    }

    /**
     * Add order address data
     */
    public function addNewUserAddress($order, $request){
        try{
            $user_addr_data = [
                'user_str_name'=> $request->street,
                'user_house_num' => $request->house,
                'user_ent_num' => $request->entrance,
                'user_apart_num' => $request->appart,
                'user_code' => $request->code
            ];
            $order->user_addresses()->create($user_addr_data);
        }
        catch(Exception $e){
            return redirect()->route('user.cart');
        }
    }

    /**
     * Calculate price for order and add to table
     * Return total price
     */
    public function addNewOrderProduct($order, $order_array){
        try{
            $total_price = 0;
            for ($i = 0; $i < count($order_array->items); $i++){
                $product_price = (((Product::getProductPrice($order_array->items[$i]->id))->product_price) * $order_array->items[$i]->count);
                $total_price = $total_price + $product_price;
                $order->order_products()->create([
                    'f_product_id' => $order_array->items[$i]->id,
                    'order_p_price' => $product_price,
                    'order_p_count' => $order_array->items[$i]->count,
                ]);
            }
            return $total_price;
        }
        catch(Exception $e){
            return 0;
        }
    }
}
