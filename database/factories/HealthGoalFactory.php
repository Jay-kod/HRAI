<?php

namespace Database\Factories;

use App\Models\HealthGoal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HealthGoal>
 */
class HealthGoalFactory extends Factory
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
            'metric' => 'bmi',
            'target' => 24,
            'start_value' => 27,
            'status' => 'active',
            'starts_at' => now(),
            'achieved_at' => null,
            'abandoned_at' => null,
        ];
    }
}
