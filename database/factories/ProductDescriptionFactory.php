<?php

namespace Database\Factories;

use App\Models\ProductDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDescriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductDescription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'f_product_id' => 1,
            'product_desc_lang_en' => $this->faker->text,
            'product_desc_lang_de' => $this->faker->text,
            'product_desc_lang_uk' => $this->faker->text,
            'product_desc_lang_ru' => $this->faker->text
        ];
    }
}
