<?php


namespace App\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\Manager;
use Exception;

class Managers
{
    use ResultDataTrait;
    /**
     * Getting all managers with count of orders
     * Output: Collection
     */

    public function getAll(): object
    {
        try {
            $managers = Manager::withCount('orders');
            if ($this->check_result($managers)) return $managers;
            else return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Input: Validated request data
     * Output: bool
     * Formation data to array and create record in db
     */
    public function add(array $request): int|bool
    {
        try {
            $data = [
                'manager_fname' => $request['f_name'],
                'manager_lname' => $request['l_name'],
                'manager_mname' => $request['m_name'],
                'manager_phone' => $request['phone'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
            ];
            $manager = Manager::create($data);
            if (isset($manager->manager_id)) return $manager->manager_id;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: Validated request data
     * Output: bool
     * Formation data to array and update record in db
     */
    public function update(array $request, int $id): bool
    {
        try {
            $data = [
                'manager_fname' => $request['f_name'],
                'manager_lname' => $request['l_name'],
                'manager_mname' => $request['m_name'],
                'manager_phone' => $request['phone'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
            ];
            $result = Manager::findOrFail($id)->update($data);
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: manager identificator
     * Output: object of the manager data
     */
    public function getById(int $id): object|bool
    {
        try {
            $manager = Manager::where('manager_id', $id)->first();
            if (isset($manager->manager_id)) return $manager;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: manager identificator
     * Output: None
     */
    public function delete(int $id): bool
    {
        try {
            $result = Manager::where('manager_id', $id)->delete();
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Input: manager identificator
     * Output: Collection of managers with orders data
     * Description: Getting orders which was claimed each manager
     */
    public function getOrdersByManagerId(int $id): object|bool
    {
        try {
            $manager = Manager::where('manager_id', $id)
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
            if (isset($manager)) return $manager;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
