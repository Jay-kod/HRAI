<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RiskAssessment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class CheckInTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_profile_can_open_check_in(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/check-in')
            ->assertOk()
            ->assertSee('Measurements')
            ->assertSee('Lifestyle')
            ->assertSee('Review');
    }

    public function test_check_in_validates_measurements_before_advancing(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Volt::test('pages.check-in')
            ->call('nextStage')
            ->assertHasErrors(['height_cm', 'weight_kg'])
            ->assertSet('stage', 1);
    }

    public function test_check_in_submits_a_persisted_assessment(): void
    {
        $this->seed();
        $user = User::factory()->create();

        $this->actingAs($user);

        Volt::test('pages.check-in')
            ->set('height_cm', 175)
            ->set('weight_kg', 80)
            ->set('systolic', 120)
            ->set('diastolic', 80)
            ->set('sleep_hours', 7)
            ->set('stress_level', 2)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('risk_assessments', ['user_id' => $user->id, 'engine' => 'rule-based']);
    }

    public function test_owner_can_view_an_assessment_result(): void
    {
        $user = User::factory()->create();
        $assessment = RiskAssessment::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('assessments.show', $assessment))
            ->assertOk()
            ->assertSee('Overall screening estimate')
            ->assertSee('educational screening estimate');
    }
}
