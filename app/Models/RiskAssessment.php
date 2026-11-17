<?php

namespace App\Models;

use App\Enums\RiskLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAssessment extends Model
{
    /** @use HasFactory<\Database\Factories\RiskAssessmentFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'biometric_data_id', 'engine', 'overall_score', 'overall_level', 'condition_results', 'risk_factors', 'assessed_at'];

    protected function casts(): array
    {
        return ['overall_score' => 'decimal:2', 'overall_level' => RiskLevel::class, 'condition_results' => 'array', 'risk_factors' => 'array', 'assessed_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function biometricData() { return $this->belongsTo(BiometricData::class); }
    public function recommendations() { return $this->hasMany(PreventiveRecommendation::class); }
    public function reportShares() { return $this->hasMany(ReportShare::class); }
}
