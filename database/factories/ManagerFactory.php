<?php

namespace Database\Factories;

use App\Models\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'manager_fname' => $this->faker->firstName,
            'manager_lname' => $this->faker->lastName,
            'manager_mname' => $this->faker->firstName,
            'manager_phone' => $this->faker->numerify('###########'),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email' => $this->faker->unique()->safeEmail,
            'remember_token' => Str::random(10),
        ];
    }
}
