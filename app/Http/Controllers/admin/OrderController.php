<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\manager\orders\UpdateRequest;
use App\Http\Requests\manager\orders\SearchRequest;
use Exception;
use App\Classes\Orders;
use App\Classes\Payments;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->orders = new Orders();
        $this->payments = new Payments();
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return view('admin.orders.index')->with('orders', $this->orders->getAll()->paginate(15));
        } else return redirect()->route('admin.login.page');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!empty($order = $this->orders->getById($id)))
            return view('admin.orders.show')->with('order', $order);
        else return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!empty($order = $this->orders->getById($id))) {
            return view('admin.orders.edit')
                ->with('order', $order)
                ->with('payment', $this->payments->getAvaliable());
        } else return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\manager\orders\UpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $validated =  $request->validated();
            if (isset($validated['type'])) {
                if ($validated['type'] == "confirm") {
                    $this->orders->updateStatus($id, "processing", NULL);
                } elseif ($validated['type'] == "cancel") {
                    $this->orders->updateStatus($id, "canceled", NULL);
                } elseif ($validated['type'] == "complete") {
                    $this->orders->updateStatus($id, "completed", NULL);
                } elseif ($validated['type'] == "created") {
                    $this->orders->updateStatus($id, "created", NULL);
                } elseif ($validated['type'] == "edit_data") {
                    $this->orders->update($id, $request, NULL);
                }
                return redirect()->route('admin.orders.index');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.login.page');
        }
    }


    /**
     * Get order by status name
     * validate if input name
     */
    public function get_by_status($name)
    {
        $statuses = array('created', 'processing', 'completed', 'canceled');
        if (in_array($name, $statuses)) {
            $orders = $this->orders->getByStatus($name)->paginate(15);
            if (count($orders) != 0) {
                return view('admin.orders.index')->with('orders', $orders);
            } else {
                $orders = null;
                return view('admin.orders.index')->with('orders', $orders);
            }
        } else return abort(404);
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
            return 0;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $this->orders->delete($id);
        return redirect()->route('admin.orders.index');
    }
}
