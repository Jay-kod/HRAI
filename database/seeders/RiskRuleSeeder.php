<?php

namespace Database\Seeders;

use App\Models\RiskRule;
use Illuminate\Database\Seeder;

class RiskRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            ['code' => 'bmi_overweight', 'label' => 'BMI is in the overweight range', 'applies_to' => ['heart', 'diabetes', 'hypertension'], 'fact_key' => 'bmi', 'operator' => 'gte', 'threshold' => 25, 'severity' => 'warning', 'points' => 8],
            ['code' => 'bmi_obesity', 'label' => 'BMI is in the obesity range', 'applies_to' => ['heart', 'diabetes', 'hypertension'], 'fact_key' => 'bmi', 'operator' => 'gte', 'threshold' => 30, 'severity' => 'critical', 'points' => 15],
            ['code' => 'bp_elevated', 'label' => 'Blood pressure is elevated', 'applies_to' => ['heart', 'hypertension'], 'fact_key' => 'systolic', 'operator' => 'gte', 'threshold' => 130, 'severity' => 'warning', 'points' => 10],
            ['code' => 'bp_high', 'label' => 'Blood pressure is high', 'applies_to' => ['heart', 'hypertension'], 'fact_key' => 'systolic', 'operator' => 'gte', 'threshold' => 140, 'severity' => 'critical', 'points' => 18],
            ['code' => 'smoking', 'label' => 'Current smoking was reported', 'applies_to' => ['heart', 'diabetes', 'hypertension'], 'fact_key' => 'smoker', 'operator' => 'truthy', 'threshold' => null, 'severity' => 'warning', 'points' => 12],
            ['code' => 'heavy_alcohol', 'label' => 'Heavy alcohol use was reported', 'applies_to' => ['heart', 'hypertension'], 'fact_key' => 'heavy_alcohol', 'operator' => 'truthy', 'threshold' => null, 'severity' => 'warning', 'points' => 8],
            ['code' => 'low_activity', 'label' => 'Low physical activity was reported', 'applies_to' => ['heart', 'diabetes'], 'fact_key' => 'phys_activity', 'operator' => 'falsey', 'threshold' => null, 'severity' => 'warning', 'points' => 8],
            ['code' => 'poor_sleep', 'label' => 'Sleep duration is below seven hours', 'applies_to' => ['heart', 'diabetes', 'hypertension'], 'fact_key' => 'sleep_hours', 'operator' => 'lt', 'threshold' => 7, 'severity' => 'warning', 'points' => 5],
            ['code' => 'family_history', 'label' => 'Family history was reported', 'applies_to' => ['heart', 'diabetes', 'hypertension'], 'fact_key' => 'family_history', 'operator' => 'truthy', 'threshold' => null, 'severity' => 'warning', 'points' => 10],
        ];

        foreach ($rules as $rule) {
            RiskRule::updateOrCreate(['code' => $rule['code']], $rule);
        }
    }
}
