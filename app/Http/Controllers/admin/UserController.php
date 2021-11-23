<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Classes\Users;
use App\Http\Requests\admin\users\SearchRequest;
use App\Http\Requests\admin\users\UpdateRequest;
use Exception;

class UserController extends Controller
{

    public function __construct()
    {
        $this->users = new Users();
        $this->middleware('auth:admin');
    }
    /**
     * Get all users
     */
    public function index()
    {
        try{
            if (count($this->users->getAll()) > 0)
                return view('admin.users.index')->with('users', $this->users->getPaginated(15));
            else return view('admin.users.index')->with('users', null);
        }
        catch(Exception $e){
            print($e);
        }
        
    }

    /**
     * Validate query data
     * Get rusults from query and return in json
     */
    public function search(SearchRequest $request){
        try{
            $validated =  $request->validated();
            $results = $this->users->search($validated['query']);
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
        try{
            if (!empty($user = $this->users->getById($id)))
                return view('admin.users.show')->with('user', $user);
            else
                return abort(404);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Get data by user id and return it to edit view
     */
    public function edit($id)
    {
        try{
            if (!empty($user = $this->users->getById($id)))
                return view('admin.users.edit')->with('user', $user);
            else
                return abort(404);
        }
        catch(Exception $e){
            return 0;
        }
    }


    /**
     * Validate data through UpdateUserRequest
     * and formation request data to update
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            if ($request->type == "edit_data"){
                $validated = $request->validated();
                $this->users->update($validated, $id);
                return redirect()->route('admin.users.index');
            }
        }
        catch(Exception $e){
            return 0;
        }
    }
    /**
     * Delete user
     */
    public function destroy($id)
    {
        try{
            $this->users->delete($id);
            return redirect()->route('admin.users.index');
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Get orders which user had created
     */
    public function orders($id){

        try{
            return view('admin.users.orders')->with('users', $this->users->getOrdersByUserId($id)->paginate(15));
        }
        catch(Exception $e){
            return 0;
        }
    }
}
