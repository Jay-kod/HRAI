<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();

        if (! Auth::user()?->isAdmin()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'form.email' => 'This portal is reserved for administrators.',
            ]);
        }

        Session::regenerate();
        $this->redirectIntended(default: route('admin.model-performance', absolute: false), navigate: true);
    }

    public function loginAsDemo(): void
    {
        $this->form->email = 'admin@example.com';
        $this->form->password = 'password';
        $this->login();
    }
}; ?>

<div class="grid bg-slate-950 lg:grid-cols-[0.95fr_1.05fr]">
    <section class="hidden bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 p-10 text-white lg:flex lg:flex-col lg:justify-between">
        <div>
            <span class="inline-flex rounded-full border border-amber-300/30 bg-amber-300/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-amber-200">Restricted access</span>
            <h1 class="mt-3 text-2xl font-bold leading-tight">HealthRisk AI control centre.</h1>
            <p class="mt-2 text-sm leading-6 text-slate-300">Review model performance and system feedback from the protected administrator workspace.</p>
        </div>
        <p class="text-sm text-slate-400">Administrator access is limited to authorised project operators.</p>
    </section>

    <section class="bg-white p-5 sm:p-8 lg:p-4">
        <div class="mx-auto max-w-md">
            <div class="mb-3">
                <p class="text-sm font-semibold uppercase tracking-widest text-amber-700">Administrator sign in</p>
                <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-950">Welcome to the control centre</h2>
                <p class="mt-1 text-xs leading-4 text-slate-600">Use your authorised administrator account to continue.</p>
            </div>

            <x-auth-session-status class="mb-5" :status="session('status')" />

            <form wire:submit="login" class="space-y-2">
                <div>
                    <x-input-label for="admin-email" :value="__('Administrator email')" />
                    <x-text-input wire:model="form.email" id="admin-email" class="mt-1 block w-full rounded-lg py-1.5 text-sm" type="email" name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="admin-password" :value="__('Password')" />
                    <x-text-input wire:model="form.password" id="admin-password" class="mt-1 block w-full rounded-lg py-1.5 text-sm" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>
                <label for="admin-remember" class="flex items-center gap-3 text-base text-slate-600">
                    <input wire:model="form.remember" id="admin-remember" type="checkbox" class="rounded border-slate-300 text-amber-700 shadow-sm focus:ring-amber-500" name="remember">
                    Remember me on this device
                </label>
                <x-primary-button wire:loading.attr="disabled" wire:target="login,loginAsDemo" class="w-full justify-center rounded-lg bg-slate-950 py-2 text-sm hover:bg-slate-800 disabled:cursor-wait disabled:opacity-70">
                    <span wire:loading.remove wire:target="login,loginAsDemo">{{ __('Enter admin centre') }}</span>
                    <span wire:loading wire:target="login,loginAsDemo" class="inline-flex items-center gap-2">
                        <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" aria-hidden="true"></span>
                        Authenticating...
                    </span>
                </x-primary-button>
            </form>

            <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-2.5">
                <p class="text-xs text-slate-600">Need to review the system demo?</p>
                <button type="button" wire:click="loginAsDemo" wire:loading.attr="disabled" wire:target="login,loginAsDemo" class="mt-1.5 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-amber-300 bg-white px-4 py-2 text-xs font-bold text-amber-900 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-400 hover:shadow-md disabled:cursor-wait disabled:opacity-70">
                    <span wire:loading.remove wire:target="login,loginAsDemo">Test Administrator</span>
                    <span wire:loading wire:target="login,loginAsDemo" class="inline-flex items-center gap-2">
                        <span class="h-4 w-4 animate-spin rounded-full border-2 border-amber-300 border-t-amber-800" aria-hidden="true"></span>
                        Authenticating...
                    </span>
                </button>
                <p class="mt-1 text-center text-[11px] text-slate-500">Uses the seeded local administrator account.</p>
            </div>

            <p class="mt-3 text-center text-xs text-slate-600">Looking for the user area? <a href="{{ route('login') }}" wire:navigate class="font-semibold text-teal-700 hover:text-teal-900">User sign in</a></p>
        </div>
    </section>
</div>
