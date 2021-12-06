<?php


namespace App\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\OrderProduct;
use Composer\Command\ExecCommand;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderProducts
{
    use ResultDataTrait;
    /**
     * Input: order identificator
     * Output: Collection of order products
     * Description: Getting products which were ordered include deleted;
     */
    public function getByOrderId(int $id): object|bool
    {
        try {
            $products = OrderProduct::where('f_order_id', $id)->withTrashed()->get();
            if ($this->check_result($products)) return $products;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: product identificator, order identificator
     * Output: Collection result of deleted order products
     * Description: Deleted ordered products by id and order id;
     */
    public function deleteByProductId(int $id, int $order_id): bool
    {
        try {
            $result = OrderProduct::where('f_product_id', $id)
                ->where('f_order_id', $order_id)->delete();
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: order identificator
     * Output: Numeric value
     * Description: Getting sum of products which were ordered
     */
    public function getSumByOrderId(int $id): float|bool
    {
        try {
            $sum = OrderProduct::where('f_order_id', $id)->withTrashed()->sum('order_p_price');
            if ($sum >= 0) return $sum;
            else return false;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Input: order identificator, count of product, price
     * Output: Result of update
     * Description: Update amount of ordered products
     */
    public function updateCountPrice(int $id, int $count, float $price): bool
    {
        try {
            $result = OrderProduct::where('f_product_id', $id)
                ->withTrashed()
                ->update([
                    'order_p_count' => $count,
                    'order_p_price' => $price
                ]);
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: None
     * Output: Collection of Products
     * Description: Getting top 5 ordered products 
     */
    public function PopularProductionAndCategories(): iterable
    {
        try {
            $result = OrderProduct::select('f_product_id', DB::raw('count(order_p_count)'))->with('products', 'products.names', 'products.categories:catg_id,catg_name_en,catg_name_de,catg_name_uk,catg_name_ru')
                ->groupBy('f_product_id')->orderBy(DB::raw('count(order_p_count)'), 'DESC')->take(5)->get();
            if ($this->check_result($result)) return $result;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }
}
