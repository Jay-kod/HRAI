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
}; ?>

<div class="grid lg:grid-cols-[0.95fr_1.05fr]">
    <section class="hidden bg-gradient-to-br from-teal-950 via-emerald-900 to-teal-700 p-10 text-white lg:flex lg:flex-col lg:justify-between">
        <div>
            <span class="inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-teal-100">For everyday users</span>
            <h1 class="mt-6 text-4xl font-bold leading-tight">Get started with a clearer picture of your health.</h1>
            <p class="mt-5 text-lg leading-8 text-teal-50">Your personal screening companion turns a simple check-in into understandable risk estimates and practical next steps.</p>
        </div>
        <div class="space-y-4 text-base text-teal-50">
            <p class="flex gap-3"><span class="text-teal-300" aria-hidden="true">✓</span> Guided four-stage check-in</p>
            <p class="flex gap-3"><span class="text-teal-300" aria-hidden="true">✓</span> Explainable risk factors</p>
            <p class="flex gap-3"><span class="text-teal-300" aria-hidden="true">✓</span> Goals and progress tracking</p>
        </div>
    </section>

    <section class="p-6 sm:p-10 lg:p-12">
        <div class="mx-auto max-w-md">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-widest text-teal-700">User sign in</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Sign in to your account</h2>
                <p class="mt-3 text-base leading-6 text-slate-600">Continue your health screening journey securely.</p>
            </div>

            <x-auth-session-status class="mb-5" :status="session('status')" />

            <form wire:submit="login" class="space-y-5">
                <div>
                    <x-input-label for="email" :value="__('Email address')" />
                    <x-text-input wire:model="form.email" id="email" class="mt-2 block w-full rounded-lg" type="email" name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <x-input-label for="password" :value="__('Password')" />
                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-teal-700 hover:text-teal-900" href="{{ route('password.request') }}" wire:navigate>Forgot password?</a>
                        @endif
                    </div>
                    <x-text-input wire:model="form.password" id="password" class="mt-2 block w-full rounded-lg" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <label for="remember" class="flex items-center gap-3 text-base text-slate-600">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 text-teal-700 shadow-sm focus:ring-teal-500" name="remember">
                    Remember me on this device
                </label>

                <x-primary-button class="w-full justify-center rounded-lg bg-teal-700 py-3 text-sm hover:bg-teal-800">
                    {{ __('Sign in') }}
                </x-primary-button>
            </form>

            <div class="mt-6 rounded-xl border border-teal-100 bg-teal-50 p-4 text-sm text-slate-700">
                <p class="font-bold text-teal-900">Demo user account</p>
                <dl class="mt-2 grid grid-cols-[auto_1fr] gap-x-3 gap-y-1">
                    <dt class="font-medium text-slate-500">Email</dt>
                    <dd class="font-mono text-xs">demo@example.com</dd>
                    <dt class="font-medium text-slate-500">Password</dt>
                    <dd class="font-mono text-xs">password</dd>
                </dl>
                <p class="mt-2 text-xs text-slate-500">For local demonstration only.</p>
            </div>

            <p class="mt-8 text-center text-base text-slate-600">New to HealthRisk AI? <a href="{{ route('register') }}" wire:navigate class="font-semibold text-teal-700 hover:text-teal-900">Create an account</a></p>
            <div class="mt-8 border-t border-slate-200 pt-5 text-center">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Administrator portal</p>
                <a href="{{ route('admin.login') }}" wire:navigate class="mt-2 inline-flex text-sm font-semibold text-slate-700 hover:text-slate-950">Sign in as an administrator <span aria-hidden="true">&rarr;</span></a>
            </div>
            <p class="mt-6 text-center text-sm leading-5 text-slate-500">Educational screening only — not a medical diagnosis.</p>
        </div>
    </section>
</div>
