<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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
    
}
