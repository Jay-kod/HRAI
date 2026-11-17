<?php

namespace App\Services;

use App\Models\PreventiveRecommendation;
use App\Models\RecommendationTemplate;
use App\Models\RiskAssessment;

class RecommendationGenerator
{
    /**
     * @param array<int, array<string, mixed>> $factors
     */
    public function generate(RiskAssessment $assessment, array $factors): void
    {
        $codes = collect($factors)->pluck('code');

        RecommendationTemplate::query()
            ->where('active', true)
            ->get()
            ->filter(fn (RecommendationTemplate $template): bool => collect($template->trigger_codes ?? [])->intersect($codes)->isNotEmpty())
            ->each(function (RecommendationTemplate $template) use ($assessment): void {
                PreventiveRecommendation::create([
                    'risk_assessment_id' => $assessment->id,
                    'template_code' => $template->code,
                    'title' => $template->title,
                    'advice' => $template->body,
                    'rationale' => $template->rationale,
                    'priority' => $template->priority,
                    'status' => 'pending',
                ]);
            });
    }
}
