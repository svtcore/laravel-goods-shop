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

    public static function getByOrderId($id){
        return OrderProduct::where('f_order_id', $id)->withTrashed()->get();
    }

    public static function deleteByProductId($id){
        return OrderProduct::where('f_product_id', $id)->delete();
    }

    public static function getSumProductsByOrderId($id){
        return OrderProduct::where('f_order_id', $id)->withTrashed()->sum('order_p_price');
    }

    public static function updateCountPrice($id, $count, $price){
        return OrderProduct::where('f_product_id', $id)->withTrashed()->update(['order_p_count' => $count, 'order_p_price' => $price]);
    }
    public static function PopularProductionAndCategories(){
        return OrderProduct::select('f_product_id', DB::raw('count(order_p_count)'))->with('products', 'products.names', 'products.categories:catg_id,catg_name_en,catg_name_de,catg_name_uk,catg_name_ru')
        ->groupBy('f_product_id')->orderBy(DB::raw('count(order_p_count)'),'DESC')->take(5)->get();
    }
}
