<?php

namespace App\Enums;

enum GoalMetric: string
{
    case WeightKg = 'weight_kg';
    case Bmi = 'bmi';
    case Systolic = 'systolic';
    case FastingGlucoseMgdl = 'fasting_glucose_mgdl';
    case RestingHeartRate = 'resting_heart_rate';
}
