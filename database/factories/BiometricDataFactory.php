<?php

namespace Database\Factories;

use App\Models\BiometricData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BiometricData>
 */
class BiometricDataFactory extends Factory
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
            'assessed_at' => now(),
            'height_cm' => 175,
            'weight_kg' => 78,
            'bmi' => 25.5,
            'systolic' => 120,
            'diastolic' => 80,
            'fasting_glucose_mgdl' => 95,
            'resting_heart_rate' => 68,
        ];
    }
}
