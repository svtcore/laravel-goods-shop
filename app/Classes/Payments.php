<?php


namespace App\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\PaymentType;
use Exception;

class Payments
{
    use ResultDataTrait;
    /**
     * Input: None
     * Output: array of payment type data
     * Description: Getting avaliable payment types and convert data to array
     */
    public function getAvaliable(): iterable
    {
        try {
            $payment = PaymentType::where('pay_t_exst', 1)->get();
            $payment_array = array();
            foreach ($payment as $pay) {
                $payment_array[$pay->pay_t_id] = $pay->pay_t_name;
            }
            return $payment_array;
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Input: payment type id
     * Output: collection of payment types
     * Description: Getting payment type by id
     */
    public function getById(int $id): object|bool
    {
        try {
            $payment = PaymentType::where('pay_t_id', $id)->first();
            if (isset($payment->pay_t_id)) return $payment;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: payment type id
     * Output: array of payment type data
     * Description: Getting all payment types
     */
    public function getAll(): iterable
    {
        try {
            $payments = PaymentType::orderBy('pay_t_name')->get();
            if ($this->check_result($payments)) return $payments;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Input: validated request data
     * Output: None
     * Description: Add payment types data to db
     */
    public function add(array $request): int|bool
    {
        try {
            $payment = PaymentType::create([
                'pay_t_name' => $request['payment_name'],
                'pay_t_exst' => $request['payment_exst']
            ]);
            if (isset($payment->pay_t_id)) return $payment->pay_t_id;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: validated request data, payment id
     * Output: None
     * Description: Update payment types data by id
     */
    public function update(array $request, int $id): bool
    {
        try {
            $result = PaymentType::findOrFail($id)->update([
                'pay_t_name' => $request['payment_name'],
                'pay_t_exst' => $request['payment_exst']
            ]);
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: payment type id
     * Output: None
     * Description: Delete payment types data by id
     */
    public function delete(int $id): bool
    {
        try {
            $result = PaymentType::findOrFail($id)->delete();
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
