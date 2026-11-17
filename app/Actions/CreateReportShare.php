<?php

namespace App\Actions;

use App\Models\ReportShare;
use App\Models\RiskAssessment;
use App\Models\User;
use Illuminate\Support\Str;

class CreateReportShare
{
    public function execute(User $user, RiskAssessment $assessment): ReportShare
    {
        return ReportShare::create([
            'user_id' => $user->id,
            'risk_assessment_id' => $assessment->id,
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);
    }
}
