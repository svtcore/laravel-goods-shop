<?php

namespace Database\Factories;

use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderProduct::class;
    private $counter = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'f_product_id' => rand(1,5),
            'order_p_count' => rand(1,3),
            'order_p_price' => rand(150,1000)
        ];
    }
}
