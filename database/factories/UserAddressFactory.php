<?php

namespace Database\Factories;

use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_str_name' => $this->faker->streetName,
            'user_house_num' => $this->faker->buildingNumber,
            'user_ent_num' => strval(rand(1,5)),
            'user_apart_num' => strval(rand(1, 100))
        ];
    }
}
