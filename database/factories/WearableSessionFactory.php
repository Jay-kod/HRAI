<?php

namespace Database\Factories;

use App\Models\WearableSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WearableSession>
 */
class WearableSessionFactory extends Factory
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
            'source' => 'manual',
            'session_date' => today(),
            'average_heart_rate' => 68,
            'steps' => 7000,
            'sleep_hours' => 7.5,
            'raw_data' => [],
        ];
    }
}
