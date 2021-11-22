<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Http\Requests\ManagerRequest;
use App\Http\Requests\admin\managers\StoreRequest;
use App\Http\Requests\admin\managers\UpdateRequest;
use Exception;
use App\Classes\Managers;

class ManagerController extends Controller
{

    public function __construct()
    {
        $this->managers = new Managers();
        $this->middleware('auth:admin');
    }

    /**
     * Getting manager list with count of orders they taken
     */
    public function index()
    {
        $managers = $this->managers->getAll()->get();
        if (count($managers) > 0)
            return view('admin.managers')->with('managers', $this->managers->getAll()->paginate(15));
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
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $this->managers->add($validated);
        return redirect()->route('admin.managers');
    }

    /**
     * Getting data from requested id and return view with it
     */
    public function edit($id)
    {
        if (!empty($manager = $this->managers->getById($id)))
            return view('admin.manager_edit')->with('manager', $manager);
        else return abort(404);
    }

    /**
     * Validate data through Requests\ManagerRequest and 
     * run function updateManager for formation and update data
     */
    public function update(UpdateRequest $request, $id)
    {
        if ($request->type == "edit_data") {
            $validated = $request->validated();
            $this->managers->update($validated, $id);
            return redirect()->route('admin.managers');
        }
        return redirect()->back();
    }

    /**
     * Deleteting record
     */
    public function destroy($id)
    {
        $this->managers->delete($id);
        return redirect()->route('admin.managers');
    }

    /**
     * Getting orders which manager was taken
     * Return view with object data 
     */
    public function orders($id)
    {
        $orders = $this->managers->getOrdersByManagerId($id)->paginate(15);
        return view('admin.manager_orders')->with('managers', $orders);
    }
}
