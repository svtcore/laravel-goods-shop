<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Get all users
     */
    public function index()
    {
        $users = User::get();
        if (count($users) > 0)
            return view('admin.users')->with('users', User::paginate(15));
        else return view('admin.users')->with('users', null);
    }

    /**
     * Validate query data
     * Get rusults from query and return in json
     */
    public function search(){
        try{
            $query = preg_replace( '/^[a-zа-яÀ-ÿ0-9ẞ\s.#%№%()[]?!\/,-]+$/i', '', $_GET['query']);
            $results = User::searchQuery($query);
            return response()->json($results);
        }
        catch(Exception $e){
            return 0;
        }
    }
    /**
     * Get data by user id and return it to view
     */
    public function show($id)
    {
        return view('admin.user_show')->with('user', User::getUserDataById($id));
    }

    /**
     * Get data by user id and return it to edit view
     */
    public function edit($id)
    {
        return view('admin.user_edit')->with('user', User::getUserDataById($id));
    }


    /**
     * Validate data through UpdateUserRequest
     * and formation request data to update
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try{
            if ($request->type == "edit_data"){ 
                User::where('user_id', $id)->update(
                    [
                    'user_fname' => $request->f_name, 
                    'user_lname' => $request->l_name,
                    'user_phone' => $request->phone, 
                    'email' => $request->email,
                    ]);
                return redirect()->route('admin.users');
            }
        }
        catch(Exception $e){
            //
        }
        return redirect()->route('admin.users');
    }
    /**
     * Delete user
     */
    public function destroy($id)
    {
        User::find($id)->forcedelete();
        return redirect()->route('admin.users');
    }

    /**
     * Get orders which user had created
     */
    public function orders($id){

        return view('admin.user_orders')->with('users', User::getOrdersByUserId($id)->paginate(15));
    }
}
