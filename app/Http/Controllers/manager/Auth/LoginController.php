<?php

namespace App\Http\Controllers\Manager\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\ManagerAuthRequest;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function username(){
        return "manager_phone";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if (Auth::guard('manager')->check()){
            return redirect()->route('manager.orders.status', ['name' => 'created']);
        }
        else return view('manager.auth.login');
    }

    public function authenticate(ManagerAuthRequest $request)
    {
        if (Auth::guard('manager')->attempt($request->validated())) {
            return redirect()->route('manager.orders.status', ['name' => 'created']);
        }
        else{
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('manager')->logout();
        return redirect()->route('manager.login.page');
    }
}
