<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class ProductImage extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $primaryKey = "product_image_id";
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'f_product_id',
        'product_image_name',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
