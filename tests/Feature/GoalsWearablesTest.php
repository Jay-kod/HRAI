<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class GoalsWearablesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_health_goal(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Volt::test('pages.goals')
            ->set('metric', 'bmi')
            ->set('target', 24)
            ->set('start_value', 28)
            ->call('createGoal')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('health_goals', [
            'user_id' => $user->id,
            'metric' => 'bmi',
            'status' => 'active',
        ]);
    }

    public function test_user_can_generate_simulated_wearable_sessions(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Volt::test('pages.wearables')
            ->call('simulate')
            ->assertHasNoErrors();

        $this->assertSame(7, $user->wearableSessions()->count());
    }
}
