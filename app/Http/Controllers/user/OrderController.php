<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\user\orders\StoreRequest;
use Exception;
use App\Classes\Categories;
use App\Classes\Orders;
use App\Classes\Payments;
use App\Classes\Users;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{

    public $categories;

    /**
     * Load category list to navbar
     */
    public function __construct()
    {
        $this->categories = new Categories();
        $this->orders = new Orders();
        $this->payments = new Payments();
        $this->users = new Users();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if (Auth::check()) {
                $orders = $this->orders->getByUserId(Auth::id());
                if (!empty($orders->get())) {
                    if (count($orders->get()) > 0)
                        return view('user.orders.index')
                            ->with('orders', $orders->paginate(15))
                            ->with('categories', $this->categories->getAll());
                    else return view('user.orders.index')
                        ->with('orders', null)
                        ->with('categories', $this->categories->getAll());
                }
            } else return redirect()->route('login')->with('categories', $this->categories->getAll());
        }
        catch(Exception $e){
            return 0;
        }
        
    }

    /**
     * Show the form for creating a new resource.
     * Check if exist cookies with order list
     * Run function to formation cookies to array and create record
     * Return order data, payment type list and category list for nav bar
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            if (isset($_COOKIE['user_cart'])) {
                $cookies = $_COOKIE['user_cart'];
                $data_arr = $this->orders->formatCookies($cookies);
                if ($data_arr != null) {
                    return view('user.orders.cart')
                        ->with('order_products', $data_arr[1])
                        ->with('total', $data_arr[0])
                        ->with('payment', $this->payments->getAvaliable())
                        ->with('categories', $this->categories->getAll());
                } else {
                    return redirect()->route('user.orders.cart')->withCookie(Cookie::forget('user_cart'));
                }
            } else {
                return view('user.orders.cart')
                        ->with('order_products', null)
                        ->with('categories', $this->categories->getAll());
            }
        }
        catch(Exception $e){
            return 0;
        }
        
    }

    /**
     * Store a newly created resource in storage.
     * Formation order cookies to order array
     * Check if user authorized then link this order to exist user
     * else just add order and create new user
     *
     * @param  App\Http\Requests\user\orders\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            if (isset($request['submit'])) {
                $password = null;
                $validated = $request->validated();
                $order_array = $this->formatStoreCookies($validated);
                if ($order_array != null) {
                    if (Auth::check())
                        $id = Auth::id();
                    else {
                        $user_data_arr = $this->users->add($validated);
                        $password = $user_data_arr[1];
                        if ($user_data_arr[1] != null)
                            $id = $user_data_arr[0]->user_id;
                        else $id = $user_data_arr[0];
                    }
                    $id = $this->orders->add($validated, $id, $order_array);
                    return view('user.orders.finish')
                        ->with('order_id', $id)
                        ->with('password', $password)
                        ->with('categories', $this->categories->getAll());
                } else return redirect()->route('user.orders.cart');
            }
        } catch (Exception $e) {
            return redirect()->route('user.orders.cart');
        }
    }

    /**
     * Formation cookies to array for store order
     * cookies have 2 values 
     * id  - product id
     * count - count of selected product
     */
    public function formatStoreCookies($request)
    {
        try {
            if (isset($_COOKIE['user_cart'])) {
                $order_array = json_decode($_COOKIE['user_cart']);
                for ($i = 0; $i < count($order_array->items); $i++) {
                    $id = preg_replace('/[^0-9]/', '', $order_array->items[$i]->id);
                    $count = preg_replace('/[^0-9]/', '', $order_array->items[$i]->count);
                    if (!empty($id) && !empty($count)) {
                        $order_array->items[$i]->id = $id;
                        $order_array->items[$i]->count = $count;
                    }
                }
                return $order_array;
            } else return redirect()->route('user.orders.cart');
        } catch (Exception $e) {
            return redirect()->route('user.orders.cart');
        }
    }
}
