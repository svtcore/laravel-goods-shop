<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class ProductName extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $primaryKey = "product_name_id";
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'f_product_id',
        'product_name_lang_en',
        'product_name_lang_de',
        'product_name_lang_uk',
        'product_name_lang_ru',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
