<?php

namespace Database\Factories;

use App\Models\Aspiration;
use App\Models\Upvote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Upvote>
 */
class UpvoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'aspiration_id' => Aspiration::factory(),
            'voted_at' => now(),
        ];
    }
}
