<?php


namespace App\Classes;

use App\Models\PaymentType;
use Exception;

class Payments
{
    public function getAvaliable()
    {
        try {
            $payment = PaymentType::where('pay_t_exst', 1)->get();
            $payment_array = array();
            foreach ($payment as $pay) {
                $payment_array[$pay->pay_t_id] = $pay->pay_t_name;
            }
            return $payment_array;
        } catch (Exception $e) {
            return array(null);
        }
    }

    public function getById($id){
        return PaymentType::where('pay_t_id',$id)->first();
    }

    public function getAll()
    {
        return PaymentType::orderBy('pay_t_name')->get();
    }

    public function add($request)
    {
        try {
            PaymentType::create([
                'pay_t_name' => $request['payment_name'],
                'pay_t_exst' => $request['payment_exst']
            ]);
            return 1;
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to store payment data']);
        }
    }

    public function update($request, $id)
    {
        try {
            return PaymentType::find($id)->update([
                'pay_t_name' => $request['payment_name'],
                'pay_t_exst' => $request['payment_exst']
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to store payment data']);
        }
    }

    public function delete($id){
        return PaymentType::find($id)->delete();
    }
}
