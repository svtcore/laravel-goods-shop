<?php


namespace App\Classes;

use App\Models\Manager;
use Exception;

class Managers
{
    /**
     * Getting all managers with count of orders
     * Output: Collection
     */

    public function getAll()
    {
        return Manager::withCount('orders');
    }

    /**
     * Input: Validated request data
     * Output: None
     * Formation data to array and create record in db
     */
    public function add($request)
    {
        $data = [
            'manager_fname' => $request['f_name'],
            'manager_lname' => $request['l_name'],
            'manager_mname' => $request['m_name'],
            'manager_phone' => $request['phone'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ];
        Manager::create($data);
    }

    /**
     * Input: Validated request data
     * Output: None
     * Formation data to array and update record in db
     */
    public function update($request, $id)
    {
        $data = [
            'manager_fname' => $request['f_name'],
            'manager_lname' => $request['l_name'],
            'manager_mname' => $request['m_name'],
            'manager_phone' => $request['phone'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ];
        Manager::findOrFail($id)->update($data);
    }

    /**
     * Input: manager identificator
     * Output: Collection of the manager data
     */
    public function getById($id)
    {
        return Manager::where('manager_id', $id)->first();
    }

    /**
     * Input: manager identificator
     * Output: None
     */
    public function delete($id)
    {
        return Manager::where('manager_id', $id)->delete();
    }

    /**
     * Input: manager identificator
     * Output: Collection of managers with orders data
     * Description: Getting orders which was claimed each manager
     */
    public function getOrdersByManagerId($id)
    {
        return Manager::where('manager_id', $id)
            ->with(['orders' => function ($q) {
                $q->withTrashed();
            }, 'orders.payment_types' => function ($q) {
                $q->withTrashed();
            }, 'orders.user_addresses' => function ($q) {
                $q->withTrashed();
            }, 'orders.order_products' => function ($q) {
                $q->withTrashed();
            }, 'orders.order_products.products' => function ($q) {
                $q->withTrashed();
            }, 'orders.order_products.products.names' => function ($q) {
                $q->withTrashed();
            }]);
    }
}
