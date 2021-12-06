<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Classes\Orders;
use App\Classes\Payments;
use App\Http\Requests\manager\orders\UpdateRequest;
use App\Http\Requests\manager\orders\SearchRequest;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->orders = new Orders();
        $this->payments = new Payments();
        $this->middleware('auth:manager');
    }

    /**
     * Check if user authorized then redirect
     * to orders
     */
    public function index()
    {
        try{
            if (Auth::check()) {
                return view('manager.orders.index');
            } else return redirect()->route('manager.login.page');
        }
        catch(Exception $e){
            return 0;
        }

    }

    /**
     * Get order by id
     * return recived data to view 
     */
    public function show($id)
    {
        try{
            if ($order = $this->orders->getById($id))
            return view('manager.orders.show')->with('order', $order);
        else return abort(404);
        }
        catch(NotFoundHttpException $e){
            return abort(404);
        }
        catch(Exception $e){
            return abort(400);
        }

    }

    /**
     * Get order data and get available 
     * payments methods
     * Return view with both data
     */
    public function edit($id)
    {
        try{
            if ($order = $this->orders->getById($id)) {
                return view('manager.orders.edit')
                    ->with('order', $order)
                    ->with('payment', $this->payments->getAvaliable());
            } else return abort(404);
        }
        catch(NotFoundHttpException $e){
            return abort(404);
        }
        catch(Exception $e){
            return abort(400);
        }

    }

    /**
     * Checking type of update and call function
     * to update order status
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $validated =  $request->validated();
            if (isset($validated['type'])) {
                if ($validated['type'] == "confirm") {
                    $this->orders->updateStatus($id, "processing", Auth::guard('manager')->id());
                } elseif ($validated['type'] == "cancel") {
                    $this->orders->updateStatus($id, "canceled", Auth::guard('manager')->id());
                } elseif ($validated['type'] == "complete") {
                    $this->orders->updateStatus($id, "completed", Auth::guard('manager')->id());
                } elseif ($validated['type'] == "created") {
                    $this->orders->updateStatus($id, "created", Auth::guard('manager')->id());
                } elseif ($validated['type'] == "edit_data") {
                    $this->orders->update($id, $request, Auth::guard('manager')->id());
                }
                return redirect()->route('manager.orders.status', ['name' => 'created']);
            }
        } catch (Exception $e) {
            return redirect()->route('manager.login.page');
        }
    }


    /**
     * Get order by status name
     * validate if input name
     */
    public function get_by_status($name)
    {
        try{
            $statuses = array('created', 'processing', 'completed', 'canceled');
            if (in_array($name, $statuses)) {
                $orders = $this->orders->getByStatus($name)->paginate(15);
                if (count($orders) != 0) {
                    return view('manager.orders.index')->with('orders', $orders);
                } else {
                    $orders = null;
                    return view('manager.orders.index')->with('orders', $orders);
                }
            } else return abort(404);
        }
        catch(Exception $e){
            return abort(400);
        }
        
    }

    /**
     * Validate data and run search function
     * retunr object with data
     */
    public function search(SearchRequest $request)
    {
        try {
            $validated = $request->validated();
            $results = $this->orders->search($validated['query']);
            return $results;
        } catch (Exception $e) {
            return abort(400);
        }
    }
}
