<?php

namespace Database\Factories;

use App\Models\RiskRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiskRule>
 */
class RiskRuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->word(),
            'label' => fake()->sentence(3),
            'applies_to' => ['heart'],
            'fact_key' => 'bmi',
            'operator' => 'gte',
            'threshold' => 25,
            'severity' => 'warning',
            'points' => 5,
            'active' => true,
        ];
    }
}
