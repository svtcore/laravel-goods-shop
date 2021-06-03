<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentType;
use App\Http\Requests\PaymentTypeRequest;

class PaymentTypeController extends Controller
{
    /**
     * Getting all payment types with sort
     */
    public function index()
    {
        return view('admin.payments')->with('payments', PaymentType::orderBy('pay_t_name')->get());
    }

    public function create()
    {
        return view('admin.payment_new');
    }
    
    /**
     * Validate data through Requests\PaymentTypeRequest
     * format data to array and add to db
     */
    public function store(PaymentTypeRequest $request)
    {
        try{
            PaymentType::create([
                    'pay_t_name' => $request->payment_name,
                    'pay_t_exst' => $request->payment_exst
            ]);
            return redirect()->route('admin.payments');
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['message' => 'Failed to store payment data']);
        }

    }
    
    /**
     * Getting payment data by id
     * Return view with output data
     */
    public function edit($id)
    {   
        if (!empty($payment = PaymentType::where('pay_t_id',$id)->first()))
            return view('admin.payment_edit')->with('payment', $payment);
        else abort(404);
    }

    /**
     * Validate data through Requests\PaymentTypeRequest
     * format data to array and add to db
     */
    public function update(PaymentTypeRequest $request, $id)
    {
        try{
            if (!empty($payment = PaymentType::where('pay_t_id', $id))){
                PaymentType::where('pay_t_id',$id)->update([
                    'pay_t_name' => $request->payment_name,
                    'pay_t_exst' => $request->payment_exst
                ]);
            }
            return redirect()->route('admin.payments');
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['message' => 'Failed to update payment data']);
        }
    }

    /**
     * Deleting payment method
     */
    public function destroy($id)
    {
        if (!empty($payment = PaymentType::where('pay_t_id', $id)))
            $payment->delete();
        return redirect()->route('admin.payments');
    }
}
