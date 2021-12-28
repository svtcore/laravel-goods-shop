<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\payments\StoreRequest;
use App\Http\Requests\admin\payments\UpdateRequest;
use App\Classes\Payments;
use Exception;

class PaymentTypeController extends Controller
{
    /**
     * Getting all payment types with sort
     */

    private $payments;

    public function __construct()
    {
        $this->payments = new Payments();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $payments = $this->payments->getAll();
            return view('admin.payments.index')->with('payments', $payments);
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
            return view('admin.payments.create');
        }
        catch(Exception $e){
            return 0;
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\admin\payments\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try{
            $validated = $request->validated();
            $this->payments->add($validated);
            return redirect()->route('admin.payments.index');
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
            if (!empty($payment = $this->payments->getById($id)))
                return view('admin.payments.edit')->with('payment', $payment);
            else abort(404);
        }
        catch(Exception $e){
            return 0;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\admin\payments\UpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try{
            $validated = $request->validated();
            $this->payments->update($validated, $id);
            return redirect()->route('admin.payments.index');
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
            if (!empty($this->payments->getById($id)))
                $this->payments->delete($id);
            return redirect()->route('admin.payments.index');
        }
        catch(Exception $e){
            return 0;
        }
    }
}
