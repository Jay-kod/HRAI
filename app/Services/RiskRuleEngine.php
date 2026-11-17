<?php

namespace App\Services;

use App\Models\RiskRule;

class RiskRuleEngine
{
    /**
     * @param array<string, int|float|bool|null> $facts
     * @return array{factors: array<int, array<string, mixed>>, scores: array<string, int>}
     */
    public function evaluate(array $facts): array
    {
        $factors = [];
        $scores = ['heart' => 0, 'diabetes' => 0, 'hypertension' => 0];

        foreach (RiskRule::query()->where('active', true)->get() as $rule) {
            $value = $facts[$rule->fact_key] ?? null;

            if ($value === null || ! $this->matches($value, $rule->operator, $rule->threshold)) {
                continue;
            }

            foreach ($rule->applies_to as $condition) {
                $scores[$condition] = ($scores[$condition] ?? 0) + $rule->points;
            }

            $factors[] = [
                'code' => $rule->code,
                'label' => $rule->label,
                'severity' => $rule->severity->value,
                'points' => $rule->points,
                'conditions' => $rule->applies_to,
            ];
        }

        return ['factors' => $factors, 'scores' => $scores];
    }

    private function matches(int|float|bool $value, string $operator, ?float $threshold): bool
    {
        return match ($operator) {
            'truthy' => (bool) $value,
            'falsey' => ! (bool) $value,
            'gte' => $threshold !== null && $value >= $threshold,
            'gt' => $threshold !== null && $value > $threshold,
            'lte' => $threshold !== null && $value <= $threshold,
            'lt' => $threshold !== null && $value < $threshold,
            'eq' => $threshold !== null && $value == $threshold,
            default => false,
        };
    }
}
