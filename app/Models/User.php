<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = "user_id";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_fname',
        'user_lname',
        'user_phone',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders(){
        return $this->hasMany(Order::class, 'f_user_id');
    }

    public static function getUserDataById($id){
        return User::where('user_id', $id)->first();
    }

    public static function getByPhone($value){
        return User::where('user_phone', $value)->first();
    }

    public static function getById($value){
        return User::where('user_id', $value)->first();
    }

    public static function searchQuery($query){
        return User::where('user_id', 'LIKE', $query."%")
                    ->Orwhere('user_fname', 'LIKE', "%".$query."%")
                    ->Orwhere('user_lname', 'LIKE', "%".$query."%")
                    ->Orwhere('user_phone', 'LIKE', "%".$query."%")
                    ->Orwhere('email', 'LIKE', "%".$query."%")
                    ->orderby('user_id','desc')->distinct('user_id')->limit(5)->get();
    }

    public static function getOrdersByUserId($id){
        return User::where('user_id', $id)
            ->with(['orders' => function($q) {$q->withTrashed();}, 'orders.user_addresses' => function($q) {$q->withTrashed();}, 'orders.payment_types' => function($q) {$q->withTrashed();},'orders.order_products' => function($q) {$q->withTrashed();}, 'orders.order_products.products' => function($q) {$q->withTrashed();},
             'orders.order_products.products.names' => function($q) {$q->withTrashed();}])
            ->withTrashed();
    }

}
