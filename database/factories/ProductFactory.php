<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $min = 0.3;
        $max = 2.5;
        return [
            'f_catg_id'=> 1,
            'product_price' => strval(rand(50, 250)),
            'product_weight' => strval(($min+lcg_value()*(abs($max-$min)))),
            'product_exst' => $this->faker->boolean
        ];
    }
}
