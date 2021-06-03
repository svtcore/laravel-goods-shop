<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $primaryKey = "user_addr_id";

    protected $fillable = [
        'f_order_id',
        'user_str_name',
        'user_house_num',
        'user_ent_num',
        'user_apart_num',
        'user_code'
    ];

    public function orders(){
        return $this->belongsTo(Order::class);
    }
}
