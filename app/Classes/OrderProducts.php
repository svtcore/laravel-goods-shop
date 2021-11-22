<?php


namespace App\Classes;
use App\Models\OrderProduct;

class OrderProducts{

    public function getByOrderId($id){
        return OrderProduct::where('f_order_id', $id)->withTrashed()->get();
    }
    
    public function deleteByProductId($id, $order_id){
        return OrderProduct::where('f_product_id', $id)
                            ->where('f_order_id', $order_id)->delete();
    }

    public function getSumByOrderId($id){
        return OrderProduct::where('f_order_id', $id)->withTrashed()->sum('order_p_price');
    }

    public function updateCountPrice($id, $count, $price){
        return OrderProduct::where('f_product_id', $id)->withTrashed()->update(['order_p_count' => $count, 'order_p_price' => $price]);
    }

    /*public function PopularProductionAndCategories(){
        return OrderProduct::select('f_product_id', DB::raw('count(order_p_count)'))->with('products', 'products.names', 'products.categories:catg_id,catg_name_en,catg_name_de,catg_name_uk,catg_name_ru')
        ->groupBy('f_product_id')->orderBy(DB::raw('count(order_p_count)'),'DESC')->take(5)->get();
    }*/
    
}

?>