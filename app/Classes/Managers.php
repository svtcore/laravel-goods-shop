<?php


namespace App\Classes;
use App\Models\Manager;
use Exception;

class Managers{

    public function getAll(){
        return Manager::withCount('orders');
    }

    /**
     * Formation data to array and create record in db
     */
    public function add($request)
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
            Manager::create($data);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to add manager data']);
        }
    }

    public function update($request, $id)
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
            Manager::findOrFail($id)->update($data);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to update manager data']);
        }
    }

    public function getById($id){
        return Manager::where('manager_id', $id)->first();
    }

    public function delete($id)
    {
        return Manager::where('manager_id', $id)->delete();
    }

    public function getOrdersByManagerId($id){
        return Manager::where('manager_id', $id)
                        ->with(['orders' => function($q) {$q->withTrashed();}, 'orders.payment_types' => function($q) {$q->withTrashed();},'orders.user_addresses' => function($q) {$q->withTrashed();}, 'orders.order_products' => function($q) {$q->withTrashed();},'orders.order_products.products' => function($q) {$q->withTrashed();}, 'orders.order_products.products.names' => function($q) {$q->withTrashed();}]);
     }
}
