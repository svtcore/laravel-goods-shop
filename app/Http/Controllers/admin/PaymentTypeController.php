<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentType;
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

    public function index()
    {
        $payments = $this->payments->getAll();
        return view('admin.payments')->with('payments', $payments);
    }

    public function create()
    {
        return view('admin.payment_new');
    }
    
    /**
     * Validate data through Requests\PaymentTypeRequest
     * format data to array and add to db
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $this->payments->add($validated);
        return redirect()->route('admin.payments');
    }
    
    /**
     * Getting payment data by id
     * Return view with output data
     */
    public function edit($id)
    {   
        if (!empty($payment = $this->payments->getById($id)))
            return view('admin.payment_edit')->with('payment', $payment);
        else abort(404);
    }

    /**
     * Validate data through Requests\PaymentTypeRequest
     * format data to array and add to db
     */
    public function update(UpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $this->payments->update($validated, $id);
        return redirect()->route('admin.payments');
    }

    /**
     * Deleting payment method
     */
    public function destroy($id)
    {
        if (!empty($payment = $this->payments->getById($id)))
            $this->payments->delete($id);
        return redirect()->route('admin.payments');
    }
}
