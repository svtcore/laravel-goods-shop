<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if (count($this->managers->getAll()->get()) > 0)
                return view('admin.managers.index')->with('managers', $this->managers->getAll()->paginate(15));
            else return view('admin.managers.index')->with('managers', null);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return view('admin.managers.create');
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\admin\managers\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try{
            $validated = $request->validated();
            $this->managers->add($validated);
            return redirect()->route('admin.managers.index');
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            if (!empty($manager = $this->managers->getById($id)))
                return view('admin.managers.edit')->with('manager', $manager);
            else return abort(404);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\admin\managers\UpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            if ($request->type == "edit_data") {
                $validated = $request->validated();
                $this->managers->update($validated, $id);
                return redirect()->route('admin.managers.index');
            }
            return redirect()->back();
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $this->managers->delete($id);
            return redirect()->route('admin.managers.index');
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Getting orders which manager was taken
     * Return view with object data 
     */
    public function orders($id)
    {
        try{
            $orders = $this->managers->getOrdersByManagerId($id)->paginate(15);
            return view('admin.manager_orders')->with('managers', $orders);
        }
        catch(Exception $e){
            return 0;
        }
    }
}
