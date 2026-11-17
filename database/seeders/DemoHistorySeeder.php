<?php

namespace Database\Seeders;

use App\Models\BiometricData;
use App\Models\LifestyleSurvey;
use App\Models\PreventiveRecommendation;
use App\Models\RiskAssessment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoHistorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@example.com')->firstOrFail();

        if ($user->riskAssessments()->exists()) {
            return;
        }

        foreach (range(0, 2) as $offset) {
            $biometricData = BiometricData::create([
                'user_id' => $user->id,
                'assessed_at' => now()->subDays($offset * 14),
                'height_cm' => 172,
                'weight_kg' => 82 - $offset,
                'bmi' => round((82 - $offset) / ((172 / 100) ** 2), 2),
                'systolic' => 128 - ($offset * 3),
                'diastolic' => 82 - ($offset * 2),
                'fasting_glucose_mgdl' => 96,
                'resting_heart_rate' => 70 - $offset,
            ]);
            $survey = LifestyleSurvey::create([
                'user_id' => $user->id,
                'biometric_data_id' => $biometricData->id,
                'phys_activity' => $offset > 0,
                'fruit' => true,
                'veggies' => true,
                'sleep_hours' => 7,
                'stress_level' => 2,
                'diagnosed_conditions' => [],
            ]);
            $assessment = RiskAssessment::create([
                'user_id' => $user->id,
                'biometric_data_id' => $biometricData->id,
                'engine' => 'rule-based',
                'overall_score' => 42 - ($offset * 8),
                'overall_level' => $offset === 0 ? 'moderate' : 'low',
                'condition_results' => [
                    'heart' => ['probability' => 0.32 - ($offset * 0.04), 'percentile' => 42 - ($offset * 8), 'level' => $offset === 0 ? 'moderate' : 'low', 'served_model' => 'rule-based-fallback'],
                    'diabetes' => ['probability' => 0.2, 'percentile' => 20, 'level' => 'low', 'served_model' => 'rule-based-fallback'],
                    'hypertension' => ['probability' => 0.28 - ($offset * 0.03), 'percentile' => 35 - ($offset * 6), 'level' => 'moderate', 'served_model' => 'rule-based-fallback'],
                ],
                'risk_factors' => [['code' => 'bp_elevated', 'label' => 'Blood pressure is elevated', 'severity' => 'warning', 'points' => 10, 'conditions' => ['heart', 'hypertension']]],
                'assessed_at' => now()->subDays($offset * 14),
            ]);
            PreventiveRecommendation::create(['risk_assessment_id' => $assessment->id, 'template_code' => 'activity_increase', 'title' => 'Build regular activity', 'advice' => 'Aim for regular moderate physical activity.', 'rationale' => 'Regular activity supports preventive health.', 'priority' => 'medium', 'status' => 'pending']);
        }
    }
}
