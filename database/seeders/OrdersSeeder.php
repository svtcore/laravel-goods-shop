<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderProduct;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($j = 6;$j <= 1000; $j++){
                Order::factory()->create(['f_user_id' => rand(1,5), 'f_pay_t_id' => rand(1,5)]);
                OrderProduct::factory()->create(['f_product_id' => rand(1,20), 'f_order_id' => $j]);
                UserAddress::factory()->create(['f_order_id' => $j]);
            }
    }
}
