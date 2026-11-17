<?php

namespace Tests\Feature;

use App\Models\HealthGoal;
use App\Models\RiskAssessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_only_view_their_own_assessments(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $assessment = RiskAssessment::factory()->for($owner)->create();

        $this->assertTrue(Gate::forUser($owner)->allows('view', $assessment));
        $this->assertFalse(Gate::forUser($otherUser)->allows('view', $assessment));
    }

    public function test_users_can_only_update_their_own_goals(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $goal = HealthGoal::factory()->for($owner)->create();

        $this->assertTrue(Gate::forUser($owner)->allows('update', $goal));
        $this->assertFalse(Gate::forUser($otherUser)->allows('update', $goal));
    }
}
