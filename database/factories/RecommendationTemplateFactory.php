<?php

namespace Database\Factories;

use App\Models\RecommendationTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecommendationTemplate>
 */
class RecommendationTemplateFactory extends Factory
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
            'title' => fake()->sentence(4),
            'body' => fake()->paragraph(),
            'rationale' => fake()->sentence(),
            'priority' => 'medium',
            'category' => 'activity',
            'goal_metric' => 'bmi',
            'trigger_codes' => ['bmi_high'],
            'active' => true,
        ];
    }
}
