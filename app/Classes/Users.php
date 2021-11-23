<?php


namespace App\Classes;

use App\Models\User;
use Exception;

class Users
{

    /**
     * if user phone exist then return user id
     * else cretae new user
     * return user id
     */

    public function add($request)
    {
        try {
            $user_data = User::where('user_phone', $request['phone'])->first();
            if (isset($user_data->user_id))
                return array($user_data->user_id, null);
            else {
                $password = $this->generateRandomPassword();
                $data = [
                    'user_fname' => $request['f_name'],
                    'user_lname' => $request['l_name'],
                    'user_phone' => $request['phone'],
                    'password' => bcrypt($password)
                ];
                return array(User::create($data), $password);
            }
        } catch (Exception $e) {
            return redirect()->route('user.cart');
        }
    }

    public function update($request, $id)
    {
        return User::where('user_id', $id)->update(
            [
                'user_fname' => $request['f_name'],
                'user_lname' => $request['l_name'],
                'user_phone' => $request['phone'],
                'email' => $request['email'],
            ]
        );
    }

    /**
     * Generate user password
     */
    public function generateRandomPassword()
    {
        try {
            $password = '';
            $desired_length = rand(8, 12);
            for ($length = 0; $length < $desired_length; $length++) {
                $password .= chr(rand(32, 126));
            }
            return $password;
        } catch (Exception $e) {
            #return redirect()->route('user.cart');
            print("ERROR GENERATE PASSWORD");
        }
    }

    public function getAll()
    {
        return User::all();
    }

    public function getPaginated($amount)
    {
        return User::paginate($amount);
    }

    public function getByPhone($value)
    {
        return User::where('user_phone', $value)->first();
    }

    public function getById($id)
    {
        return User::where('user_id', $id)->first();
    }

    public static function search($query)
    {
        return User::where('user_id', 'LIKE', $query . "%")
            ->Orwhere('user_fname', 'LIKE', "%" . $query . "%")
            ->Orwhere('user_lname', 'LIKE', "%" . $query . "%")
            ->Orwhere('user_phone', 'LIKE', "%" . $query . "%")
            ->Orwhere('email', 'LIKE', "%" . $query . "%")
            ->orderby('user_id', 'desc')->distinct('user_id')->limit(5)->get();
    }

    public static function getOrdersByUserId($id)
    {
        return User::where('user_id', $id)
            ->with([
                'orders' => function ($q) {
                    $q->withTrashed();
                }, 'orders.user_addresses' => function ($q) {
                    $q->withTrashed();
                }, 'orders.payment_types' => function ($q) {
                    $q->withTrashed();
                }, 'orders.order_products' => function ($q) {
                    $q->withTrashed();
                }, 'orders.order_products.products' => function ($q) {
                    $q->withTrashed();
                },
                'orders.order_products.products.names' => function ($q) {
                    $q->withTrashed();
                }
            ])
            ->withTrashed();
    }

    public function delete($id){
        return User::find($id)->forcedelete();
    }
}
