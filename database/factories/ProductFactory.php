<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'user_id' => 1,
            'category_id' => Category::pluck('id')->random(),
            'name' => $this->faker->name,
            'title' => $this->faker->title,
            'upc' => $this->faker->ean13,
            'description' => $this->faker->paragraph(4),
            'short_description' => $this->faker->realText($this->faker->numberBetween(10, 20)),
            'price' => $this->faker->randomFloat(4, 0, 30),
            'discount' => $this->faker->randomFloat(8, 0, 5),
            'stock' => $this->faker->numberBetween(10, 500),
            'sales' => $this->faker->numberBetween(0, 100),
            'delivery_fee' => $this->faker->randomFloat(4, 0, 20),
        ];
    }
}
