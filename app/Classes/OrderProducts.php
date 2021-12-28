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
     * Getting products which were ordered include deleted
     * 
     * @param int $id
     * @return object|bool
     * 
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
     * Deleted ordered products by id and order id
     * 
     * @param int $id
     * @param int $order_id
     * @return bool
     * 
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
     * Getting sum of products which were ordered
     * 
     * @param int $id
     * @return float|bool
     * 
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
     * Update amount of ordered products
     * 
     * @param int $id
     * @param int $count
     * @param int $price
     * @return bool
     * 
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
     * Getting top 5 ordered products
     * 
     * @param null
     * @return Collection
     *  
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
