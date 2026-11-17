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
            'condition_results' => [
                'heart' => ['probability' => 0.2, 'percentile' => 20, 'level' => 'low', 'served_model' => 'rule-based-fallback'],
                'diabetes' => ['probability' => 0.15, 'percentile' => 15, 'level' => 'low', 'served_model' => 'rule-based-fallback'],
                'hypertension' => ['probability' => 0.25, 'percentile' => 25, 'level' => 'low', 'served_model' => 'rule-based-fallback'],
            ],
            'risk_factors' => [],
            'assessed_at' => now(),
        ];
    }
}
