<?php


namespace App\Classes;
use App\Models\Product;

class Products{

    public function getById($id){
        return Product::where('product_id', $id)->with('categories', 'names','images', 'descriptions')->first();
    }

    public function getPrice($id){
        return Product::select('product_price')->where('product_id', $id)->withTrashed()->first();
    }

    public static function getRandom($count){
        return Product::where('product_exst', 1)->with('names','images','descriptions')->inRandomOrder()->take($count)->get();
    }

    public static function getProductsData(){
        return Product::with('names', 'images', 'descriptions', 'categories')->orderby('product_id', 'desc');
    }

    public static function getProductById($id){
        return Product::where('product_id', $id)->with('categories','names','images', 'descriptions')->get();
    }

    public static function search($query){
        return Product::join('product_names', 'f_product_id', '=','product_id')
                        ->join('categories', 'f_catg_id', '=','catg_id')
                        ->where('product_name_lang_en' , 'LIKE', '%'.$query.'%')
                        ->OrWhere('product_name_lang_de' , 'LIKE', '%'.$query.'%')
                        ->OrWhere('product_name_lang_uk' , 'LIKE', '%'.$query.'%')
                        ->OrWhere('product_name_lang_ru' , 'LIKE', '%'.$query.'%')
                        ->limit(5)->orderby('products.created_at', 'desc')->get();
    }
}

?>