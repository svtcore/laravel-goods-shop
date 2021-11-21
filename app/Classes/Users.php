<?php


namespace App\Classes;
use App\Models\User;
use Exception;

class Users{

    /**
     * if user phone exist then return user id
     * else cretae new user
     * return user id
     */

    public function add($request){
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
}
