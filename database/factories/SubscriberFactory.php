<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscriber>
 */
class SubscriberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'phone' => $this->faker->phoneNumber,
            'group' => $this->faker->randomElement(['email', 'sms', 'whatsapp']),
            'is_verified' => $this->faker->boolean,
            'verification_code' => $this->faker->uuid,
            'expires_at' => $this->faker->dateTimeBetween('now', '+15 days'),
        ];
    }
}
