<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\user\orders\StoreRequest;
use Exception;
use App\Classes\Categories;
use App\Classes\Orders;
use App\Classes\Payments;
use App\Classes\Users;

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
     * Get user orders
     * if not authorized redirect to login page
     */
    public function index()
    {
        if (Auth::check()) {
            $orders = $this->orders->getByUserId(Auth::id());
            if (!empty($orders->get())) {
                if (count($orders->get()) > 0)
                    return view('user.orders')
                        ->with('orders', $orders->paginate(15))
                        ->with('categories', $this->categories->getAll());
                else return view('user.orders')
                    ->with('orders', null)
                    ->with('categories', $this->categories->getAll());
            }
        } else return redirect()->route('login')->with('categories', $this->categories);
    }

    /**
     * Check if exist cookies with order list
     * Run function to formation cookies to array and create record
     * Return order data, payment type list and category list for nav bar
     */
    public function create(Request $request)
    {
        if (isset($_COOKIE['user_cart'])) {
            $cookies = $_COOKIE['user_cart'];
            $data_arr = $this->orders->formatCookies($cookies);
            if ($data_arr != null) {
                return view('user.cart')
                    ->with('order_products', $data_arr[1])
                    ->with('total', $data_arr[0])
                    ->with('payment', $this->payments->getAvaliable())
                    ->with('categories', $this->categories->getAll());
            } else {
                return redirect()->route('user.order.cart')->withCookie(Cookie::forget('user_cart'));
            }
        } else {
            return view('user.cart')
                    ->with('order_products', null)
                    ->with('categories', $this->categories->getAll());
        }
    }

    /**
     * Validate date through Request\StoreOrderRequest
     * Formation order cookies to order array
     * Check if user authorized then link this order to exist user
     * else just add order and create new user
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
                    return view('user.order_complete')
                        ->with('order_id', $id)
                        ->with('password', $password)
                        ->with('categories', $this->categories->getAll());
                } else return redirect()->route('user.order.cart');
            }
        } catch (Exception $e) {
            return redirect()->route('user.order.cart');
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
            } else return redirect()->route('user.cart');
        } catch (Exception $e) {
            return redirect()->route('user.cart');
        }
    }
}
