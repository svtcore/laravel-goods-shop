<?php


namespace App\Classes;

use App\Models\User;
use Exception;

class Users
{
    /**
     * Input: validated request data
     * Output: user id
     * Description: Add user data, if user phone exist then return user id
     * else cretae new user
     */
    public function add($request)
    {
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
    }

    /**
     * Input: validated request data, user id
     * Output: user id
     * Description: updated user data
     */
    public function update($request, $id)
    {
        User::where('user_id', $id)->update(
            [
                'user_fname' => $request['f_name'],
                'user_lname' => $request['l_name'],
                'user_phone' => $request['phone'],
                'email' => $request['email'],
            ]
        );
    }

    /**
     * Input: None
     * Output: string
     * Description: Generate user password
     */
    public function generateRandomPassword()
    {
        $password = '';
        $desired_length = rand(8, 12);
        for ($length = 0; $length < $desired_length; $length++) {
            $password .= chr(rand(32, 126));
        }
        return $password;
    }

    /**
     * Input: None
     * Output: Collection
     * Description: Getting all users
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * Input: amount of records per page
     * Output: Paginated collection
     * Description: Getting all users
     */
    public function getPaginated($amount)
    {
        return User::paginate($amount);
    }

    /**
     * Input: user phone
     * Output: Collection
     * Description: Getting user by phone number
     */
    public function getByPhone($value)
    {
        return User::where('user_phone', $value)->first();
    }

    /**
     * Input: user id
     * Output: Collection
     * Description: Getting user by user id
     */
    public function getById($id)
    {
        return User::where('user_id', $id)->first();
    }

    /**
     * Input: query 
     * Output: collection of search result
     * Description: Getting first 5 result of search query
     */
    public function search($query)
    {
        return User::where('user_id', 'LIKE', $query . "%")
            ->Orwhere('user_fname', 'LIKE', "%" . $query . "%")
            ->Orwhere('user_lname', 'LIKE', "%" . $query . "%")
            ->Orwhere('user_phone', 'LIKE', "%" . $query . "%")
            ->Orwhere('email', 'LIKE', "%" . $query . "%")
            ->orderby('user_id', 'desc')->distinct('user_id')->limit(5)->get();
    }

    /**
     * Input: user id 
     * Output: collection orders
     * Description: Getting user orders by user id
     */
    public function getOrdersByUserId($id)
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

    /**
     * Input: user id 
     * Output: collection orders
     * Description: Delete user data
     */
    public function delete($id)
    {
        User::findOrFail($id)->forcedelete();
    }
}
