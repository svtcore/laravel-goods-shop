<?php


namespace App\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\User;
use Exception;

class Users
{
    use ResultDataTrait;
    /**
     * Add user data, if user phone exist then return user id
     * else cretae new user
     * 
     * @param array $request
     * @return array
     * 
     */
    public function add(array $request): iterable
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
            return array();
        }
    }

    /**
     * updated user data
     * 
     * @param array $request
     * @param int $id
     * @return bool
     *
     */
    public function update(array $request, int $id): bool
    {
        try {
            $result = User::where('user_id', $id)->update([
                'user_fname' => $request['f_name'],
                'user_lname' => $request['l_name'],
                'user_phone' => $request['phone'],
                'email' => $request['email'],
            ]);
            if ($result) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Generate user password
     * 
     * @param null
     * @return string
     * 
     */
    public function generateRandomPassword(): string
    {
        $password = '';
        $desired_length = rand(8, 12);
        for ($length = 0; $length < $desired_length; $length++) {
            $password .= chr(rand(32, 126));
        }
        return $password;
    }

    /**
     * Getting all users
     * 
     * @param null
     * @return Collection
     * 
     */
    public function getAll(): iterable
    {
        try {
            $users = User::all();
            if ($this->check_result($users)) return $users;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Getting all users
     * 
     * @param int $amount
     * @return Collectiion
     * 
     */
    public function getPaginated(int $amount)
    {
        try {
            return User::paginate($amount);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Getting user by phone number
     * 
     * @param string $value
     * @return object|bool
     * 
     */
    public function getByPhone(string $value): object|bool
    {
        try {
            $user = User::where('user_phone', $value)->first();
            if (isset($user->user_id)) return $user;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Getting user by user id
     * 
     * @param int $id
     * @return object|bool
     * 
     */
    public function getById(int $id): object|bool
    {
        try {
            $user = User::where('user_id', $id)->first();
            if (isset($user->user_id)) return $user;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Getting first 5 result of search query
     * 
     * @param string $query 
     * @return Collection
     * 
     */
    public function search(string $query): iterable
    {
        try {
            $users = User::where('user_id', 'LIKE', $query . "%")
                ->Orwhere('user_fname', 'LIKE', "%" . $query . "%")
                ->Orwhere('user_lname', 'LIKE', "%" . $query . "%")
                ->Orwhere('user_phone', 'LIKE', "%" . $query . "%")
                ->Orwhere('email', 'LIKE', "%" . $query . "%")
                ->orderby('user_id', 'desc')->distinct('user_id')->limit(5)->get();
            if ($this->check_result($users)) return $users;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Getting user orders by user id
     * 
     * @param int $id
     * @return Collection
     * 
     */
    public function getOrdersByUserId($id): object|iterable
    {
        try {
            $result = User::where('user_id', $id)
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
            if (isset($result)) return $result;
            else return array();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Delete user data
     * 
     * @param int $id
     * @return Collection
     * 
     */
    public function delete(int $id): bool
    {
        try {
            $user = User::findOrFail($id)->forcedelete();
            if ($user) return true;
            else return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
