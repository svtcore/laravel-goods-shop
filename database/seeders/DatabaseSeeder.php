<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\Admin;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\PaymentType;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ProductImage;
use App\Models\ProductName;
use App\Models\ProductDescription;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'admins' => 5,
            'users' => 15,
            'managers' => 20,
            'categories' => 5,
            'products' => 30,
            'payments' => 5,
            'orders' => 80
        ];
        /**
         * Create manual credentials
         */
        Admin::factory()->create([
            'admin_fname' => 'John',
            'admin_lname' => 'Doe',
            'admin_phone' => '380112233444',
            'password' => bcrypt('adminpassword'),
            'email' => 'admin@admin.com',
        ]);
        User::factory()->create([
            'user_fname' => 'First',
            'user_lname' => 'User',
            'user_phone' => '1234567890',
            'password' => bcrypt('userpassword'),
            'email' => 'user@user.com',
        ]);
        Manager::factory()->create([
            'manager_fname' => 'First',
            'manager_lname' => 'Manager',
            'manager_mname' => 'Name',
            'manager_phone' => '380223344555',
            'password' => bcrypt('managerpassword'),
        ]);
        Admin::factory($data['admins'])->create();
        User::factory($data['users'])->create();
        Manager::factory($data['managers'])->create();
        Category::factory($data['categories'])->create();
        $pr_counter = 1;
        for ($j = 1;$j <=$data['products']; $j++){
            if ($pr_counter <= $data['categories']){
                Product::factory()->create(['f_catg_id' => $pr_counter]);
                $pr_counter++;
            }
            else{
                $pr_counter = 1;
                Product::factory()->create(['f_catg_id' => $pr_counter]);
                $pr_counter++;
            }
        }
        for ($j = 1;$j <=$data['products']; $j++){
            ProductName::factory()->create(['f_product_id' => $j]);
            ProductDescription::factory()->create(['f_product_id' => $j]);
            ProductImage::factory(4)->create(['f_product_id' => $j]);
        }
        PaymentType::factory($data['payments'])->create();

        for ($j = 1;$j <= $data['orders']; $j++){
                Order::factory()->create(['f_user_id' => rand(1,$data['users']), 'f_manager_id' => rand(1, $data['managers']),'f_pay_t_id' => rand(1,$data['payments'])]);
                for ($i = 1;$i <=rand(1,5);$i++)
                    OrderProduct::factory()->create(['f_product_id' => rand(1,$data['products']), 'f_order_id' => $j]);
                UserAddress::factory()->create(['f_order_id' => $j]);
        }
    }
}
