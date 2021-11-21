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
}
