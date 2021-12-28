<?php

namespace App\Http\Controllers\Admin\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\AdminAuthRequest;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //define new login field
    public function username(){
        return "admin_phone";
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if (Auth::guard('admin')->check()){
            return redirect()->route('admin.home');
        }
        else return view('admin.auth.login');
    }
    
    /**
     *  Validate data and authenticate user
     */
    public function authenticate(AdminAuthRequest $request)
    {
        if (Auth::guard('admin')->attempt($request->validated())) {
            return redirect()->route('admin.home');
        }
        else{
            return redirect()->route('admin.login.page');
        }
    }
    
    public function logout()
    {
        Session::flush();
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login.page');
    }
}
