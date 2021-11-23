<?php


namespace App\Classes;

use App\Models\PaymentType;

class Payments
{
    /**
     * Input: None
     * Output: array of payment type data
     * Description: Getting avaliable payment types and convert data to array
     */
    public function getAvaliable()
    {
        $payment = PaymentType::where('pay_t_exst', 1)->get();
        $payment_array = array();
        foreach ($payment as $pay) {
            $payment_array[$pay->pay_t_id] = $pay->pay_t_name;
        }
        return $payment_array;
    }

    /**
     * Input: payment type id
     * Output: collection of payment types
     * Description: Getting payment type by id
     */
    public function getById($id)
    {
        return PaymentType::where('pay_t_id', $id)->first();
    }

    /**
     * Input: payment type id
     * Output: array of payment type data
     * Description: Getting all payment types
     */
    public function getAll()
    {
        return PaymentType::orderBy('pay_t_name')->get();
    }

    /**
     * Input: validated request data
     * Output: None
     * Description: Add payment types data to db
     */
    public function add($request)
    {
        PaymentType::create([
            'pay_t_name' => $request['payment_name'],
            'pay_t_exst' => $request['payment_exst']
        ]);
    }

    /**
     * Input: validated request data, payment id
     * Output: None
     * Description: Update payment types data by id
     */
    public function update($request, $id)
    {
        PaymentType::findOrFail($id)->update([
            'pay_t_name' => $request['payment_name'],
            'pay_t_exst' => $request['payment_exst']
        ]);
    }

    /**
     * Input: payment type id
     * Output: None
     * Description: Delete payment types data by id
     */
    public function delete($id)
    {
        PaymentType::findOrFail($id)->delete();
    }
}
