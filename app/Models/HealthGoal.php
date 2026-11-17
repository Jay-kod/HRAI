<?php

namespace App\Models;

use App\Enums\GoalMetric;
use App\Enums\GoalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthGoal extends Model
{
    /** @use HasFactory<\Database\Factories\HealthGoalFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'metric', 'target', 'start_value', 'status', 'starts_at', 'achieved_at', 'abandoned_at'];

    protected function casts(): array
    {
        return ['metric' => GoalMetric::class, 'target' => 'decimal:2', 'start_value' => 'decimal:2', 'status' => GoalStatus::class, 'starts_at' => 'datetime', 'achieved_at' => 'datetime', 'abandoned_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
