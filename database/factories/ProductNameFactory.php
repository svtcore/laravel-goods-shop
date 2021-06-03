<?php

namespace Database\Factories;

use App\Models\ProductName;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductNameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductName::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'f_product_id' => 1,
            'product_name_lang_en' => $this->faker->company,
            'product_name_lang_de' => $this->faker->company,
            'product_name_lang_uk' => $this->faker->company,
            'product_name_lang_ru' => $this->faker->company
        ];
    }
}
