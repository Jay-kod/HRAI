<?php

namespace App\Policies;

use App\Models\RiskAssessment;
use App\Models\User;

class RiskAssessmentPolicy
{
    public function view(User $user, RiskAssessment $assessment): bool
    {
        return $user->isAdmin() || $user->isProvider() || $assessment->user_id === $user->id;
    }

    public function update(User $user, RiskAssessment $assessment): bool
    {
        return $user->isAdmin() && $assessment->user_id !== $user->id;
    }

    public function delete(User $user, RiskAssessment $assessment): bool
    {
        return $user->isAdmin() || $assessment->user_id === $user->id;
    }
}
