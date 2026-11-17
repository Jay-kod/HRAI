<?php

namespace Database\Factories;

use App\Models\PreventiveRecommendation;
use App\Models\RiskAssessment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PreventiveRecommendation>
 */
class PreventiveRecommendationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'risk_assessment_id' => RiskAssessment::factory(),
            'template_code' => 'activity_increase',
            'title' => 'Increase daily activity',
            'advice' => 'Aim for regular moderate activity.',
            'rationale' => 'Activity supports cardiovascular health.',
            'priority' => 'medium',
            'status' => 'pending',
            'completed_at' => null,
        ];
    }
}
