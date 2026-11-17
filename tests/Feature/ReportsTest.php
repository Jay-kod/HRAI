<?php

namespace Tests\Feature;

use App\Models\ReportShare;
use App\Models\RiskAssessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_a_report_share(): void
    {
        $user = User::factory()->create();
        $assessment = RiskAssessment::factory()->for($user)->create();

        $this->actingAs($user);

        Volt::test('pages.reports.show', ['assessment' => $assessment])
            ->call('createShare')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('report_shares', [
            'user_id' => $user->id,
            'risk_assessment_id' => $assessment->id,
        ]);
    }

    public function test_active_share_can_render_a_public_report(): void
    {
        $user = User::factory()->create();
        $assessment = RiskAssessment::factory()->for($user)->create();
        $share = ReportShare::factory()->for($user)->for($assessment)->create();

        $this->get(route('shared.show', $share->token))
            ->assertOk()
            ->assertSee('educational screening estimate')
            ->assertSee('HealthRisk AI screening report');
    }
}
