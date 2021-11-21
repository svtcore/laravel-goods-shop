<?php


namespace App\Classes;
use App\Models\Category;
class Categories{

    public function getAll(){
        return Category::all();
    }

    public function getById($id){
        return Category::where('catg_id', $id)->with('products','products.names', 'products.images', 'products.descriptions');
    }
}

?>