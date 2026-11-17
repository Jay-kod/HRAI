<?php

namespace Database\Factories;

use App\Models\ReportShare;
use App\Models\RiskAssessment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportShare>
 */
class ReportShareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'risk_assessment_id' => RiskAssessment::factory(),
            'token' => fake()->unique()->sha256(),
            'provider_email' => fake()->safeEmail(),
            'expires_at' => now()->addDays(7),
            'revoked_at' => null,
            'last_viewed_at' => null,
        ];
    }
}
