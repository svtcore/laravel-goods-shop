<?php

namespace App\Http\Controllers\User\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\UserAuthRequest;
use App\Classes\Categories;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function username(){
        return "user_phone";
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $this->categories = new Categories();
        return view('user.auth.login')
            ->with('categories', $this->categories->getAll());
    }

    public function authenticate(UserAuthRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            return redirect()->route('user.main.page');
        }
        else{
            return redirect()->back()->withErrors(['login_status' => "Incorrect login or password"]);
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('user.main.page');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
