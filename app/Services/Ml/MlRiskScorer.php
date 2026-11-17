<?php

namespace App\Services\Ml;

class MlRiskScorer
{
    /**
     * @param array<string, array<string, mixed>> $results
     */
    public function overall(array $results): array
    {
        $score = collect($results)->avg(fn (array $result): float => (float) ($result['percentile'] ?? 0));

        return [
            'score' => round($score, 2),
            'level' => $score >= 67 ? 'high' : ($score >= 34 ? 'moderate' : 'low'),
        ];
    }
}
