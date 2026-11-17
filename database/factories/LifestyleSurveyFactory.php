<?php

namespace Database\Factories;

use App\Models\LifestyleSurvey;
use App\Models\BiometricData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LifestyleSurvey>
 */
class LifestyleSurveyFactory extends Factory
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
            'biometric_data_id' => BiometricData::factory(),
            'smoker' => false,
            'heavy_alcohol' => false,
            'phys_activity' => true,
            'fruit' => true,
            'veggies' => true,
            'sleep_hours' => 7,
            'stress_level' => 3,
            'family_history' => false,
            'stroke' => false,
            'diagnosed_conditions' => [],
        ];
    }
}
