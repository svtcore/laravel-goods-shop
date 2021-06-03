<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class ProductDescription extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $primaryKey = "product_desc_id";
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'f_product_id',
        'product_desc_lang_en',
        'product_desc_lang_de',
        'product_desc_lang_uk',
        'product_desc_lang_ru'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
