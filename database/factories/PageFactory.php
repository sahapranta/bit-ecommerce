<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => '<p>' . implode('</p><p>', $this->faker->paragraphs(rand(4, 8))) . '</p>',
            'subtitle' => $this->faker->sentence,
            'meta_description' => $this->faker->sentence,
            'meta_keywords' => implode(',', $this->faker->words(4)),
        ];
    }
}
