<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Getting count of orders from n days ago to now
     * counted by each state of orders
     * return array with count of each states
     */
    public function Orders($days){
        try{
            $states = ['created', 'processing','canceled', 'completed'];
            $results = [];
            foreach($states as $state)
                $results[$days."_days"][$state] = Order::getDayCount($state, date('Y-m-d 00:00:00', strtotime('-'.$days.' days')) , date('Y-m-d 23:59:59'));
            $results[$days."_days"]['total'] = array_sum($results[$days."_days"]);
            return $results;
        }
        catch(Exception $e)
        {
            return array(0);
        }
        
    }

    /**
     * Getting sum of money from n days ago to now
     * return array with sum of orders with state 'completed'
     */
    public function Incomes($days){
        try{
            $results[$days."_days"]['total'] = Order::getMoneyData(date('Y-m-d 00:00:00', strtotime('-'.$days.' days')) , date('Y-m-d 23:59:59'));
            return $results;
        }
        catch(Exception $e){
            return array(null);
        }

    }
    
    /**
     * unite all array from 7, 14, 30, 90 days and return to view
     */
    public function index()
    {
        $orders = array_merge($today = $this->Orders(0), $_7 = $this->Orders(7), 
        $_14 = $this->Orders(14), $_30 = $this->Orders(30), $_90 = $this->Orders(90), $_180 = $this->Orders(180));
        
        $incomes = array_merge($today = $this->Incomes(0), $_7 = $this->Incomes(7), 
        $_14 = $this->Incomes(14), $_30 = $this->Incomes(30), $_90 = $this->Incomes(90), $_180 = $this->Incomes(180));
        return view('admin.home')->with('orders', $orders)->with('incomes', $incomes)->with('prodcat', OrderProduct::PopularProductionAndCategories());
    }
}
