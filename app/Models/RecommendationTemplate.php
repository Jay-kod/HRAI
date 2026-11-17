<?php

namespace App\Models;

use App\Enums\GoalMetric;
use App\Enums\Priority;
use App\Enums\RecCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\RecommendationTemplateFactory> */
    use HasFactory;

    protected $fillable = ['code', 'title', 'body', 'rationale', 'priority', 'category', 'goal_metric', 'trigger_codes', 'active'];

    protected function casts(): array
    {
        return ['priority' => Priority::class, 'category' => RecCategory::class, 'goal_metric' => GoalMetric::class, 'trigger_codes' => 'array', 'active' => 'boolean'];
    }
}
