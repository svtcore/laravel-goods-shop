<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Http\Requests\ManagerRequest;
use App\Http\Requests\ManagerUpdateRequest;

class ManagerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Getting manager list with count of orders they taken
     */
    public function index()
    {
        $manage = Manager::withCount('orders')->get();
        if (count($manage) > 0)
            return view('admin.managers')->with('managers', Manager::withCount('orders')->paginate(15));
        else return view('admin.managers')->with('managers', null);
    }

    public function create()
    {
        return view('admin.manager_new');
    }

    /**
     * Validate request data through Requests\ManagerRequest
     * and if data validate 
     * run the function addManager for formation and record data
     */
    public function store(ManagerRequest $request)
    {
        $this->addManager($request);
        return redirect()->route('admin.managers');
    }

    /**
     * Getting data from requested id and return view with it
     */
    public function edit($id)
    {
        $data = Manager::where('manager_id', $id)->first();
        if (!empty($data))
            return view('admin.manager_edit')->with('manager', $data);
        else return abort(404);
    }

    /**
     * Validate data through Requests\ManagerRequest and 
     * run function updateManager for formation and update data
     */
    public function update(ManagerUpdateRequest $request, $id)
    {
        if ($request->type == "edit_data"){
            $this->updateManager($request, $id);
            return redirect()->route('admin.managers');
        }
        return redirect()->back();
    }

    /**
     * Deleteting record
     */
    public function destroy($id)
    {
        Manager::where('manager_id', $id)->delete();
        return redirect()->route('admin.managers');
    }

    /**
     * Getting orders which manager was taken
     * Return view with object data 
     */
    public function orders($id){
        $orders = Manager::getOrdersByManagerId($id)->paginate(15);
        return view('admin.manager_orders')->with('managers', $orders);
    }

    /**
     * Formation data to array and create record in db
     */
    public function addManager($request){
        try{
            $data = [
                'manager_fname' => $request->f_name, 
                'manager_lname' => $request->l_name,
                'manager_mname' => $request->m_name,
                'manager_phone' => $request->phone, 
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ];
            Manager::create($data);
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['message' => 'Failed to add manager data']);
        }
    }


    /**
     * Formation data to array and create record in db
     */
    public function updateManager($request, $id){
        try{
            $data = [
                'manager_fname' => $request->f_name, 
                'manager_lname' => $request->l_name,
                'manager_mname' => $request->m_name,
                'manager_phone' => $request->phone, 
                'email' => $request->email,
            ];
            if ($request->password != null || !empty($request->password)){
                $data['password'] = bcrypt($request->password);
            }
            Manager::where('manager_id', $id)->update($data);
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['message' => 'Failed to update manager data']);
        }
    }
}
