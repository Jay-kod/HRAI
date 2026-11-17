<?php

namespace Database\Seeders;

use App\Models\RecommendationTemplate;
use Illuminate\Database\Seeder;

class RecommendationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['code' => 'activity_increase', 'title' => 'Build regular activity', 'body' => 'Aim for regular moderate physical activity that fits your current ability.', 'rationale' => 'Regular activity supports heart and metabolic health.', 'priority' => 'medium', 'category' => 'activity', 'goal_metric' => null, 'trigger_codes' => ['low_activity', 'bmi_overweight', 'bmi_obesity']],
            ['code' => 'nutrition_basics', 'title' => 'Strengthen everyday nutrition', 'body' => 'Choose more vegetables, fruit, fibre-rich foods, and water while limiting highly processed foods.', 'rationale' => 'Balanced eating supports long-term preventive health.', 'priority' => 'medium', 'category' => 'nutrition', 'goal_metric' => null, 'trigger_codes' => ['bmi_overweight', 'bmi_obesity']],
            ['code' => 'sleep_routine', 'title' => 'Review your sleep routine', 'body' => 'Work toward a consistent sleep schedule and discuss persistent sleep problems with a professional.', 'rationale' => 'Sleep is linked with energy, stress, and metabolic health.', 'priority' => 'low', 'category' => 'sleep', 'goal_metric' => null, 'trigger_codes' => ['poor_sleep']],
            ['code' => 'blood_pressure_followup', 'title' => 'Discuss blood pressure', 'body' => 'Consider arranging a blood-pressure review with a qualified healthcare professional.', 'rationale' => 'Repeated elevated readings deserve appropriate professional review.', 'priority' => 'high', 'category' => 'medical', 'goal_metric' => 'systolic', 'trigger_codes' => ['bp_elevated', 'bp_high']],
            ['code' => 'smoking_support', 'title' => 'Explore smoking support', 'body' => 'If you want to stop smoking, consider evidence-based support from a qualified professional.', 'rationale' => 'Stopping smoking can support cardiovascular health.', 'priority' => 'high', 'category' => 'medical', 'goal_metric' => null, 'trigger_codes' => ['smoking']],
        ];

        foreach ($templates as $template) {
            RecommendationTemplate::updateOrCreate(['code' => $template['code']], $template + ['active' => true]);
        }
    }
}
