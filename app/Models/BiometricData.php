<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricData extends Model
{
    /** @use HasFactory<\Database\Factories\BiometricDataFactory> */
    use HasFactory;

    protected $table = 'biometric_data';

    protected $fillable = ['user_id', 'assessed_at', 'height_cm', 'weight_kg', 'bmi', 'systolic', 'diastolic', 'fasting_glucose_mgdl', 'resting_heart_rate'];

    protected function casts(): array
    {
        return ['assessed_at' => 'datetime', 'height_cm' => 'decimal:2', 'weight_kg' => 'decimal:2', 'bmi' => 'decimal:2', 'fasting_glucose_mgdl' => 'decimal:2'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function lifestyleSurvey() { return $this->hasOne(LifestyleSurvey::class); }
    public function riskAssessments() { return $this->hasMany(RiskAssessment::class); }
}
