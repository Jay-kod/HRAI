<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="grid lg:grid-cols-[0.95fr_1.05fr]">
    <section class="hidden bg-gradient-to-br from-teal-950 via-emerald-900 to-teal-700 p-10 text-white lg:flex lg:flex-col lg:justify-between">
        <div>
            <span class="inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-teal-100">Your health, your pace</span>
            <h1 class="mt-6 text-4xl font-bold leading-tight">Get started with us.</h1>
            <p class="mt-5 text-lg leading-8 text-teal-50">Create your personal space and take a few simple steps toward more informed preventive care.</p>
        </div>
        <div class="grid grid-cols-3 gap-3 text-center text-xs font-semibold">
            <div class="rounded-2xl bg-white p-4 text-slate-900"><span class="mx-auto mb-2 flex h-7 w-7 items-center justify-center rounded-full bg-teal-100 text-teal-800">1</span>Account</div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-4 text-teal-50"><span class="mx-auto mb-2 flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-teal-100">2</span>Profile</div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-4 text-teal-50"><span class="mx-auto mb-2 flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-teal-100">3</span>Check-in</div>
        </div>
    </section>

    <section class="p-6 sm:p-10 lg:p-12">
        <div class="mx-auto max-w-md">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-widest text-teal-700">User registration</p>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Create your account</h2>
                <p class="mt-3 text-base leading-6 text-slate-600">Set up your private space for educational health screening.</p>
            </div>

            <form wire:submit="register" class="space-y-5">
                <div>
                    <x-input-label for="name" :value="__('Full name')" />
                    <x-text-input wire:model="name" id="name" class="mt-2 block w-full rounded-lg" type="text" name="name" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('Email address')" />
                    <x-text-input wire:model="email" id="email" class="mt-2 block w-full rounded-lg" type="email" name="email" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input wire:model="password" id="password" class="mt-2 block w-full rounded-lg" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                    <x-text-input wire:model="password_confirmation" id="password_confirmation" class="mt-2 block w-full rounded-lg" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                <x-primary-button class="w-full justify-center rounded-lg bg-teal-700 py-3 text-sm hover:bg-teal-800">{{ __('Create account') }}</x-primary-button>
            </form>

            <p class="mt-8 text-center text-base text-slate-600">Already registered? <a href="{{ route('login') }}" wire:navigate class="font-semibold text-teal-700 hover:text-teal-900">Sign in</a></p>
            <p class="mt-6 text-center text-sm leading-5 text-slate-500">Educational screening only — not a medical diagnosis.</p>
        </div>
    </section>
</div>
