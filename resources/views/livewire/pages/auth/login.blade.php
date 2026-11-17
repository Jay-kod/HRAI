<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function loginAsDemo(): void
    {
        $this->form->email = 'demo@example.com';
        $this->form->password = 'password';
        $this->login();
    }
}; ?>

<div class="grid lg:grid-cols-[0.95fr_1.05fr]">
    <section class="hidden bg-gradient-to-br from-teal-950 via-emerald-900 to-teal-700 p-10 text-white lg:flex lg:flex-col lg:justify-between">
        <div>
            <span class="inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-teal-100">For everyday users</span>
            <h1 class="mt-3 text-2xl font-bold leading-tight">Get started with a clearer picture of your health.</h1>
            <p class="mt-2 text-sm leading-6 text-teal-50">Your personal screening companion turns a simple check-in into understandable risk estimates and practical next steps.</p>
        </div>
        <div class="space-y-3 text-sm text-teal-50">
            <p class="flex gap-3"><span class="text-teal-300" aria-hidden="true">✓</span> Guided four-stage check-in</p>
            <p class="flex gap-3"><span class="text-teal-300" aria-hidden="true">✓</span> Explainable risk factors</p>
            <p class="flex gap-3"><span class="text-teal-300" aria-hidden="true">✓</span> Goals and progress tracking</p>
        </div>
    </section>

    <section class="p-5 sm:p-8 lg:p-4">
        <div class="mx-auto max-w-md">
            <div class="mb-3">
                <p class="text-sm font-semibold uppercase tracking-widest text-teal-700">User sign in</p>
                <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-900">Sign in to your account</h2>
                <p class="mt-1 text-xs leading-4 text-slate-600">Continue your health screening journey securely.</p>
            </div>

            <x-auth-session-status class="mb-5" :status="session('status')" />

            <form wire:submit="login" class="space-y-2">
                <div>
                    <x-input-label for="email" :value="__('Email address')" />
                    <x-text-input wire:model="form.email" id="email" class="mt-1 block w-full rounded-lg py-1.5 text-sm" type="email" name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <x-input-label for="password" :value="__('Password')" />
                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-teal-700 hover:text-teal-900" href="{{ route('password.request') }}" wire:navigate>Forgot password?</a>
                        @endif
                    </div>
                    <x-text-input wire:model="form.password" id="password" class="mt-1 block w-full rounded-lg py-1.5 text-sm" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <label for="remember" class="flex items-center gap-3 text-base text-slate-600">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 text-teal-700 shadow-sm focus:ring-teal-500" name="remember">
                    Remember me on this device
                </label>

                <x-primary-button wire:loading.attr="disabled" wire:target="login,loginAsDemo" class="w-full justify-center rounded-lg bg-teal-700 py-2 text-sm hover:bg-teal-800 disabled:cursor-wait disabled:opacity-70">
                    <span wire:loading.remove wire:target="login,loginAsDemo">{{ __('Sign in') }}</span>
                    <span wire:loading wire:target="login,loginAsDemo" class="inline-flex items-center gap-2">
                        <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" aria-hidden="true"></span>
                        Authenticating...
                    </span>
                </x-primary-button>
            </form>

            <div class="mt-3 rounded-xl border border-teal-100 bg-teal-50 p-2.5">
                <p class="text-xs text-slate-600">Want to preview the app?</p>
                <button type="button" wire:click="loginAsDemo" wire:loading.attr="disabled" wire:target="login,loginAsDemo" class="mt-1.5 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-teal-200 bg-white px-4 py-2 text-xs font-bold text-teal-800 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-300 hover:shadow-md disabled:cursor-wait disabled:opacity-70">
                    <span wire:loading.remove wire:target="login,loginAsDemo">Test User</span>
                    <span wire:loading wire:target="login,loginAsDemo" class="inline-flex items-center gap-2">
                        <span class="h-4 w-4 animate-spin rounded-full border-2 border-teal-300 border-t-teal-700" aria-hidden="true"></span>
                        Authenticating...
                    </span>
                </button>
                <p class="mt-1 text-center text-[11px] text-slate-500">Uses the seeded local demo account.</p>
            </div>

            <p class="mt-3 text-center text-xs text-slate-600">New to HealthRisk AI? <a href="{{ route('register') }}" wire:navigate class="font-semibold text-teal-700 hover:text-teal-900">Create an account</a></p>
            <div class="mt-3 border-t border-slate-200 pt-2 text-center">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Administrator portal</p>
                <a href="{{ route('admin.login') }}" wire:navigate class="mt-1 inline-flex text-xs font-semibold text-slate-700 hover:text-slate-950">Sign in as an administrator <span aria-hidden="true">&rarr;</span></a>
            </div>
            <p class="mt-2 text-center text-[11px] leading-4 text-slate-500">Educational screening only — not a medical diagnosis.</p>
        </div>
    </section>
</div>
