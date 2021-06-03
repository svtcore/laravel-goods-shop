<?php

namespace Database\Factories;

use App\Models\DeliveryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'delv_t_name' => $this->faker->cityPrefix,
            'delv_t_exst' => $this->faker->boolean
        ];
    }
}
