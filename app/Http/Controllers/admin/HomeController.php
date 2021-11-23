<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Classes\Orders;
use App\Classes\OrderProducts;
use Exception;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->orders = new Orders();
        $this->order_prod = new OrderProducts();
        $this->middleware('auth:admin');
    }
    /**
     * Getting count of orders from n days ago to now
     * counted by each state of orders
     * return array with count of each states
     */
    public function orders($days){
        try{
            $states = ['created', 'processing','canceled', 'completed'];
            $results = [];
            foreach($states as $state)
                $results[$days."_days"][$state] = $this->orders->getDayCount($state, date('Y-m-d 00:00:00', strtotime('-'.$days.' days')) , date('Y-m-d 23:59:59'));
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
    public function incomes($days){
        try{
            $results[$days."_days"]['total'] = $this->orders->getMoneyData(date('Y-m-d 00:00:00', strtotime('-'.$days.' days')) , date('Y-m-d 23:59:59'));
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
        $orders = array_merge($today = $this->orders(0), $_7 = $this->orders(7), 
        $_14 = $this->orders(14), $_30 = $this->orders(30), $_90 = $this->orders(90), $_180 = $this->orders(180));
        
        $incomes = array_merge($today = $this->incomes(0), $_7 = $this->incomes(7), 
        $_14 = $this->incomes(14), $_30 = $this->incomes(30), $_90 = $this->incomes(90), $_180 = $this->incomes(180));
        return view('admin.home')->with('orders', $orders)->with('incomes', $incomes)->with('prodcat', $this->order_prod->PopularProductionAndCategories());
    }
}
