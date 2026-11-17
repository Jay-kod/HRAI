<?php

namespace App\Services;

use App\Models\BiometricData;
use App\Models\LifestyleSurvey;
use App\Models\User;

class FeatureMapper
{
    /**
     * @return array<string, int|float>
     */
    public function map(User $user, BiometricData $biometricData, LifestyleSurvey $survey): array
    {
        $age = $user->date_of_birth?->age ?? 18;

        return [
            'age_cat' => min(13, max(1, (int) ceil($age / 5))),
            'sex' => $user->gender?->value === 'male' ? 1 : 0,
            'bmi' => (float) $biometricData->bmi,
            'high_bp' => (int) (($biometricData->systolic ?? 0) >= 130 || ($biometricData->diastolic ?? 0) >= 80),
            'high_chol' => 0,
            'smoker' => (int) $survey->smoker,
            'hvy_alcohol' => (int) $survey->heavy_alcohol,
            'phys_activity' => (int) $survey->phys_activity,
            'fruit' => (int) $survey->fruit,
            'veggies' => (int) $survey->veggies,
            'gen_health' => 3,
            'mental_health' => (int) ($survey->stress_level ?? 1),
            'physical_health' => 1,
            'diabetes' => in_array('diabetes', $survey->diagnosed_conditions ?? [], true) ? 1 : 0,
        ];
    }
}
