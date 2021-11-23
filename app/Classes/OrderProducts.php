<?php


namespace App\Classes;
use App\Models\OrderProduct;
use DB;

class OrderProducts{

    /**
     * Input: order identificator
     * Output: Collection of order products
     * Description: Getting products which were ordered include deleted;
     */
    public function getByOrderId($id){
        return OrderProduct::where('f_order_id', $id)->withTrashed()->get();
    }
    
    /**
     * Input: product identificator, order identificator
     * Output: Collection result of deleted order products
     * Description: Deleted ordered products by id and order id;
     */
    public function deleteByProductId($id, $order_id){
        return OrderProduct::where('f_product_id', $id)
                            ->where('f_order_id', $order_id)->delete();
    }

    /**
     * Input: order identificator
     * Output: Numeric value
     * Description: Getting sum of products which were ordered
     */
    public function getSumByOrderId($id){
        return OrderProduct::where('f_order_id', $id)->withTrashed()->sum('order_p_price');
    }

    /**
     * Input: order identificator, count of product, price
     * Output: Result of update
     * Description: Update amount of ordered products
     */
    public function updateCountPrice($id, $count, $price){
        return OrderProduct::where('f_product_id', $id)->withTrashed()->update(['order_p_count' => $count, 'order_p_price' => $price]);
    }

    /**
     * Input: None
     * Output: Collection of Products
     * Description: Getting top 5 ordered products 
     */
    public function PopularProductionAndCategories(){
        return OrderProduct::select('f_product_id', DB::raw('count(order_p_count)'))->with('products', 'products.names', 'products.categories:catg_id,catg_name_en,catg_name_de,catg_name_uk,catg_name_ru')
        ->groupBy('f_product_id')->orderBy(DB::raw('count(order_p_count)'),'DESC')->take(5)->get();
    }
    
}
