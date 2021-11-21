<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Product extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $primaryKey = "product_id";
    protected $softCascade = ['descriptions', 'images', 'names'];

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'f_catg_id',
        'product_price',
        'product_weight',
        'product_exst',
    ];

    public function descriptions()
    {
        return $this->hasOne(ProductDescription::class, 'f_product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'f_product_id');
    }

    public function names()
    {
        return $this->hasOne(ProductName::class, 'f_product_id');
    }

    public function categories(){
        return $this->belongsTo(Category::class, 'f_catg_id');
    }

    public function order_product()
    {
        return $this->hasMany(Order::class, 'f_product_id');
    }

    /*public static function getProductPrice($id){
        return Product::select('product_price')->where('product_id', $id)->withTrashed()->first();
    }*/

    /*public static function getRandomProducts($count){
        return Product::where('product_exst', 1)->with('names','images','descriptions')->inRandomOrder()->take($count)->get();
    }*/

    public static function getProductsData(){
        return Product::with('names', 'images', 'descriptions', 'categories')->orderby('product_id', 'desc');
    }

    public static function getProductById($id){
        return Product::where('product_id', $id)->with('categories','names','images', 'descriptions')->get();
    }

    
}
