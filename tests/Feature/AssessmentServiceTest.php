<?php

namespace Tests\Feature;

use App\Actions\RunAssessment;
use App\Models\BiometricData;
use App\Models\LifestyleSurvey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_assessment_uses_rule_fallback_when_ml_service_is_unavailable(): void
    {
        $this->seed();
        $user = User::factory()->create();
        $biometricData = BiometricData::factory()->for($user)->create();
        $survey = LifestyleSurvey::factory()->for($user)->for($biometricData)->create();

        $assessment = app(RunAssessment::class)->execute($user, $biometricData, $survey);

        $this->assertSame('rule-based', $assessment->engine);
        $this->assertSame(['heart', 'diabetes', 'hypertension'], array_keys($assessment->condition_results));
        $this->assertNotEmpty($assessment->risk_factors);
        $this->assertGreaterThan(0, $assessment->recommendations()->count());
    }
}
