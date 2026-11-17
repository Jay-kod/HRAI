<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/user/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.login')
            ->assertSee('Sign in to your account')
            ->assertSee('Test User')
            ->assertSee('Authenticating...')
            ->assertSee('Educational screening only');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_admin_login_screen_is_separate_from_user_login(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSeeVolt('pages.auth.admin-login')
            ->assertSee('Administrator sign in')
            ->assertSee('Test Administrator')
            ->assertSee('Authenticating...')
            ->assertSee('Restricted access');
    }

    public function test_only_admins_can_use_the_admin_login_screen(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.admin-login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component->assertHasErrors(['form.email']);
        $this->assertGuest();
    }

    public function test_admins_can_use_the_admin_login_screen(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);

        $component = Volt::test('pages.auth.admin-login')
            ->set('form.email', $admin->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.model-performance', absolute: false));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_cannot_enter_the_user_workspace(): void
    {
        $admin = User::factory()->create(['role' => Role::Admin]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertForbidden();
    }

    public function test_regular_user_cannot_enter_the_admin_workspace(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.overview'))
            ->assertForbidden();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'wrong-password');

        $component->call('login');

        $component
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_navigation_menu_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/user/dashboard');

        $response
            ->assertOk()
            ->assertSeeVolt('layout.navigation');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('layout.navigation');

        $component->call('logout');

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
