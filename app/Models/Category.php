<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps =  true;
    protected $primaryKey = "catg_id";


    protected $fillable = [
        'f_city_id',
        'catg_name_en', 
        'catg_name_de',
        'catg_name_uk',
        'catg_name_ru',
        ];

    public function products(){
        return $this->hasMany(Product::class, 'f_catg_id');
    }

    public static function getById($id){
        return Category::where('catg_id', $id)->with('products','products.names', 'products.images', 'products.descriptions');
    }
}
