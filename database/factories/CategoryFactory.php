<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'catg_name_en'=> $this->faker->country,
            'catg_name_de'=> $this->faker->country,
            'catg_name_uk'=> $this->faker->country,
            'catg_name_ru'=> $this->faker->country,
        ];
    }
}
