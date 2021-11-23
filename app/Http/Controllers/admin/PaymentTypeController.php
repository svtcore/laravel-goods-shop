<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\payments\StoreRequest;
use App\Http\Requests\admin\payments\UpdateRequest;
use App\Classes\Payments;
use Exception;

class PaymentTypeController extends Controller
{
    /**
     * Getting all payment types with sort
     */

    private $payments;

    public function __construct()
    {
        $this->payments = new Payments();
    }

    public function index()
    {
        try{
            $payments = $this->payments->getAll();
            return view('admin.payments.index')->with('payments', $payments);
        }
        catch(Exception $e){
            return 0;
        }

    }

    public function create()
    {
        try{
            return view('admin.payments.create');
        }
        catch(Exception $e){
            return 0;
        }
    }
    
    /**
     * Validate data through Requests\PaymentTypeRequest
     * format data to array and add to db
     */
    public function store(StoreRequest $request)
    {
        try{
            $validated = $request->validated();
            $this->payments->add($validated);
            return redirect()->route('admin.payments.index');
        }
        catch(Exception $e){
            return 0;
        }
    }
    
    /**
     * Getting payment data by id
     * Return view with output data
     */
    public function edit($id)
    {   
        try{
            if (!empty($payment = $this->payments->getById($id)))
                return view('admin.payments.edit')->with('payment', $payment);
            else abort(404);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Validate data through Requests\PaymentTypeRequest
     * format data to array and add to db
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $validated = $request->validated();
            $this->payments->update($validated, $id);
            return redirect()->route('admin.payments.index');
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Deleting payment method
     */
    public function destroy($id)
    {
        try{
            if (!empty($this->payments->getById($id)))
                $this->payments->delete($id);
            return redirect()->route('admin.payments.index');
        }
        catch(Exception $e){
            return 0;
        }
    }
}
