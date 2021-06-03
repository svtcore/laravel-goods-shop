<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $primaryKey = "pay_t_id";

    protected $fillable = [
        'pay_t_name',
        'pay_t_exst'
    ];

    public static function getAvailable(){
        return PaymentType::where('pay_t_exst', 1)->get();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'f_pay_t_id');
    }
}
