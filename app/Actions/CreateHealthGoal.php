<?php

namespace App\Actions;

use App\Models\HealthGoal;
use App\Models\User;

class CreateHealthGoal
{
    /**
     * @param array{metric: string, target: numeric, start_value: numeric|null} $data
     */
    public function execute(User $user, array $data): HealthGoal
    {
        return $user->healthGoals()->create([
            'metric' => $data['metric'],
            'target' => $data['target'],
            'start_value' => $data['start_value'],
            'status' => 'active',
            'starts_at' => now(),
        ]);
    }
}
