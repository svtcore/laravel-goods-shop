<?php

namespace Database\Factories;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'pay_t_name' => $this->faker->creditCardType,
            'pay_t_exst' => $this->faker->boolean
        ];
    }
}
