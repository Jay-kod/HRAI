<?php

namespace App\Services;

use App\Models\User;
use App\Models\WearableSession;

class WearableSimulator
{
    public function simulate(User $user, int $days = 7): int
    {
        $created = 0;

        for ($offset = $days - 1; $offset >= 0; $offset--) {
            $date = today()->subDays($offset);

            if ($user->wearableSessions()->whereDate('session_date', $date)->exists()) {
                continue;
            }

            WearableSession::create([
                'user_id' => $user->id,
                'source' => 'simulated',
                'session_date' => $date,
                'average_heart_rate' => 64 + (($offset * 3) % 12),
                'steps' => 5200 + (($offset * 731) % 4200),
                'sleep_hours' => 6.5 + (($offset * 0.25) % 1.5),
                'raw_data' => ['simulated' => true],
            ]);
            $created++;
        }

        return $created;
    }
}
