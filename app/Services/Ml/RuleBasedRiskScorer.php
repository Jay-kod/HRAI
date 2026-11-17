<?php

namespace App\Services\Ml;

class RuleBasedRiskScorer
{
    /**
     * @param array<string, int> $scores
     * @return array<string, array<string, mixed>>
     */
    public function score(array $scores): array
    {
        $results = [];

        foreach (['heart', 'diabetes', 'hypertension'] as $condition) {
            $score = min(100, $scores[$condition] ?? 0);
            $results[$condition] = [
                'probability' => round($score / 100, 4),
                'percentile' => (float) $score,
                'level' => $score >= 67 ? 'high' : ($score >= 34 ? 'moderate' : 'low'),
                'served_model' => 'rule-based-fallback',
            ];
        }

        return $results;
    }
}
