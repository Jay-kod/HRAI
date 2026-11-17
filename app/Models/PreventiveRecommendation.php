<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\RecStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreventiveRecommendation extends Model
{
    /** @use HasFactory<\Database\Factories\PreventiveRecommendationFactory> */
    use HasFactory;

    protected $fillable = ['risk_assessment_id', 'template_code', 'title', 'advice', 'rationale', 'priority', 'status', 'completed_at'];

    protected function casts(): array
    {
        return ['priority' => Priority::class, 'status' => RecStatus::class, 'completed_at' => 'datetime'];
    }

    public function riskAssessment() { return $this->belongsTo(RiskAssessment::class); }
}
