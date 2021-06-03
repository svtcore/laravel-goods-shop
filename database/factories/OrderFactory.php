<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;
    private $counter = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $states = array('created','processing', 'completed', 'canceled');
        $key = array_rand($states);
        return [
            'order_full_price' => rand(150,1000),
            'order_note' => $this->faker->text,
            'order_phone' => $this->faker->numerify('###########'),
            'order_status' => $states[$key],
            'created_at' => $this->faker->dateTimeBetween($startDate = '-6 month', $endDate = 'now')
        ];
    }
}
