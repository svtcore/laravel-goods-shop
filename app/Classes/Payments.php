<?php


namespace App\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\PaymentType;
use Exception;

class Payments
{
    use ResultDataTrait;
    /**
     * Getting avaliable payment types and convert data to array
     * 
     * @param null
     * @return array
     * 
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
     * Getting payment type by id
     * 
     * @param int $id
     * @return object|bool
     * 
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
     * Getting all payment types
     * 
     * @param null
     * @return array
     * 
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
     * Add payment types data to db
     * 
     * @param array $request
     * @return int|bool
     * 
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
     * Update payment types data by id
     * 
     * @param array $request
     * @param int $id
     * @return bool
     * 
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
     * Delete payment types data by id
     * 
     * @param int $id
     * @return bool
     * 
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
