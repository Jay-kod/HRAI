<?php

namespace Database\Factories;

use App\Models\RiskAssessment;
use App\Models\BiometricData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiskAssessment>
 */
class RiskAssessmentFactory extends Factory
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
            'engine' => 'hybrid',
            'overall_score' => 22.5,
            'overall_level' => 'low',
            'condition_results' => ['heart' => ['score' => 20, 'level' => 'low']],
            'risk_factors' => [],
            'assessed_at' => now(),
        ];
    }
}
