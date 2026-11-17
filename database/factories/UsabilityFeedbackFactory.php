<?php

namespace Database\Factories;

use App\Models\UsabilityFeedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UsabilityFeedback>
 */
class UsabilityFeedbackFactory extends Factory
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
            'ease_of_use' => 4,
            'usefulness' => 4,
            'confidence' => 4,
            'intention' => 4,
            'comment' => fake()->sentence(),
            'submitted_at' => now(),
        ];
    }
}
