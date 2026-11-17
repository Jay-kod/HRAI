<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AdminFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_model_performance(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($admin)
            ->get(route('admin.model-performance'))
            ->assertOk()
            ->assertSee('Model Performance');
    }

    public function test_regular_user_cannot_view_model_performance(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.model-performance'))
            ->assertForbidden();
    }

    public function test_admin_can_view_the_ai_control_center(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);
        Http::fake([
            '*/health' => Http::response(['status' => 'ok', 'conditions' => ['heart', 'diabetes', 'hypertension'], 'synthetic' => true]),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ai'))
            ->assertOk()
            ->assertSee('AI Control Center')
            ->assertSee('Dataset overview')
            ->assertSee('Model features')
            ->assertSee('Run test prediction');
    }

    public function test_regular_user_cannot_view_the_ai_control_center(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.ai'))
            ->assertForbidden();
    }

    public function test_admin_logout_redirects_to_admin_login(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($admin);

        Volt::test('admin.logout-button')
            ->call('logout')
            ->assertRedirect(route('admin.login', absolute: false));

        $this->assertGuest();
    }

    public function test_user_can_submit_feedback(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Volt::test('pages.feedback')
            ->set('ease_of_use', 5)
            ->set('usefulness', 4)
            ->set('confidence', 4)
            ->set('intention', 5)
            ->set('comment', 'Clear and useful.')
            ->call('submitFeedback')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('usability_feedback', ['user_id' => $user->id, 'usefulness' => 4]);
    }
}
