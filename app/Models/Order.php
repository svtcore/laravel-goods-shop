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

    public static function getByUserId($id){
        return Order::where('f_user_id', $id)
        ->with(['payment_types' => function($q) { $q->withTrashed();}, 'order_products' => function($q) { $q->withTrashed();},'user_addresses','order_products.products' => function($q) { $q->withTrashed();},'order_products.products.names' => function($q) { $q->withTrashed();}, 'order_products.products.descriptions' => function($q) { $q->withTrashed();},'order_products.products.categories' => function($q) { $q->withTrashed();}])
        ->orderby('order_id','desc');
    }

    public static function updatePrice($id, $data){
        return Order::where('order_id', $id)->update(['order_full_price' => $data]);
    }

    public static function getOrdersByStatus($status){
        return Order::where('order_status', $status)
                    ->with(['users',  'payment_types' => function($q) { $q->withTrashed();}, 'user_addresses', 'order_products' => function($q) { $q->withTrashed();}, 'order_products.products' => function($q) { $q->withTrashed();}, 'order_products.products.names' => function($q) { $q->withTrashed();}])
                    ->orderby('order_id','asc');
    }

    public static function updateStatus($id, $status, $manager){
        return Order::where('order_id', $id)->update(
            ['order_status' => $status, 
            'f_manager_id' => $manager
            ]);
    }

    public static function getById($id){
        return Order::where('order_id', $id)
                    ->with(['order_products' => function($q) { $q->withTrashed();},
                    'user_addresses',
                    'order_products.products' => function($q) { $q->withTrashed();},
                    'order_products.products.names' => function($q) { $q->withTrashed();},
                    'order_products.products.descriptions' => function($q) { $q->withTrashed();},
                    'order_products.products.categories' => function($q) { $q->withTrashed();},
                    'payment_types' => function($q) { $q->withTrashed();}])
                    ->orderby('order_id','desc')->withTrashed()->first();
    }

    public static function search($query){
        return Order::select('orders.order_id', 'orders.order_phone', 'orders.created_at','user_addresses.user_str_name',
                            'user_addresses.user_house_num', 'user_addresses.user_apart_num')
                    ->join('user_addresses', 'f_order_id', '=','order_id')
                    ->join('users', 'f_user_id', '=','user_id')
                    ->where('order_id', 'LIKE', $query."%")
                    ->Orwhere('order_phone', 'LIKE', "%".$query."%")
                    ->Orwhere('order_fname', 'LIKE', "%".$query."%")
                    ->Orwhere('order_lname', 'LIKE', "%".$query."%")
                    ->Orwhere('user_phone', 'LIKE', "%".$query."%")
                    ->Orwhere('user_str_name', 'LIKE', "%".$query."%")
                    ->orderby('order_id','desc')->distinct('order_id')->limit(5)
                    ->withTrashed()->get();
    }

    public static function getAllOrdersData(){
        return Order::with(['users', 'payment_types' => function($q) { $q->withTrashed(); }, 'user_addresses', 'order_products', 'order_products.products',
            'order_products.products.names', 'order_products.products.images', 'order_products.products.descriptions'])
            ->withTrashed()->orderby('order_id','desc');
    }

    public static function getDayCount($status, $from, $to){
        return Order::where('order_status', $status)->whereBetween('created_at', [$from, $to])->count();
    }

    public static function getMoneyData($from, $to){
        return Order::where('order_status', "completed")->whereBetween('created_at', [$from, $to])->sum('order_full_price');
    }
}
