<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class OrderProduct extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $primaryKey = "order_p_id";

    protected $fillable = [
        'f_product_id',
        'f_order_id',
        'order_p_price',
        'order_p_count'
    ];

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'f_product_id');
    }
}
