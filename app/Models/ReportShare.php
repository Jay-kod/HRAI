<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportShare extends Model
{
    /** @use HasFactory<\Database\Factories\ReportShareFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'risk_assessment_id', 'token', 'provider_email', 'expires_at', 'revoked_at', 'last_viewed_at'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime', 'revoked_at' => 'datetime', 'last_viewed_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function riskAssessment() { return $this->belongsTo(RiskAssessment::class); }
}
