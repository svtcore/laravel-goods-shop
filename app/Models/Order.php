<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use DB;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $primaryKey = "order_id";


    protected $fillable = [
    'f_user_id',
    'f_pay_t_id',
    'f_manager_id',
    'order_fname',
    'order_lname',
    'order_phone',
    'order_code',
    'order_full_price',
    'order_note',
    'order_status'];


    public function getCreatedAtAttribute($value){
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }
    public function getUpdatedAtAttribute($value){
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }

    public function user_addresses()
    {
        return $this->hasOne(UserAddress::class, 'f_order_id');
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class, 'f_order_id');
    }

    public function managers(){
        return $this->belongsTo(Manager::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function payment_types(){
        return $this->belongsTo(PaymentType::class, 'f_pay_t_id');
    }
}
