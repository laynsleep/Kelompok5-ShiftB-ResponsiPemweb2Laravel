<?php

namespace Database\Factories;

use App\Enums\AspirationStatus;
use App\Models\Aspiration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aspiration>
 */
class AspirationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(mt_rand(4, 8)),
            'description' => fake()->paragraphs(mt_rand(1, 3), true),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(AspirationStatus::cases()),
        ];
    }

    /**
     * Set the aspiration status to pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AspirationStatus::Pending,
        ]);
    }

    /**
     * Set the aspiration status to resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AspirationStatus::Resolved,
        ]);
    }
}
