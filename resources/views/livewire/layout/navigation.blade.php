<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public bool $confirmingLogout = false;

    public function confirmLogout(): void
    {
        $this->confirmingLogout = true;
    }

    public function cancelLogout(): void
    {
        $this->confirmingLogout = false;
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="border-b border-white/10 bg-slate-900">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-teal-300" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('check-in')" :active="request()->routeIs('check-in')" wire:navigate>
                        {{ __('Check-in') }}
                    </x-nav-link>
                    <x-nav-link :href="route('history')" :active="request()->routeIs('history')" wire:navigate>
                        {{ __('History') }}
                    </x-nav-link>
                    <x-nav-link :href="route('goals')" :active="request()->routeIs('goals')" wire:navigate>
                        {{ __('Goals') }}
                    </x-nav-link>
                    <x-nav-link :href="route('wearables')" :active="request()->routeIs('wearables')" wire:navigate>
                        {{ __('Wearables') }}
                    </x-nav-link>
                    <x-nav-link :href="route('feedback')" :active="request()->routeIs('feedback')" wire:navigate>
                        {{ __('Feedback') }}
                    </x-nav-link>
                    @if (auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.model-performance')" :active="request()->routeIs('admin.*')" wire:navigate>
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-md border border-transparent bg-slate-900 px-3 py-2 text-sm font-medium leading-4 text-slate-400 transition hover:text-white focus:outline-none">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            <span class="ms-2 rounded-full bg-teal-400/10 px-2 py-1 text-xs text-teal-300">{{ auth()->user()->role->value }}</span>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="confirmLogout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-400 transition hover:bg-white/10 hover:text-white focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-white/10 bg-slate-900 sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('check-in')" :active="request()->routeIs('check-in')" wire:navigate>
                {{ __('Check-in') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('history')" :active="request()->routeIs('history')" wire:navigate>
                {{ __('History') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('goals')" :active="request()->routeIs('goals')" wire:navigate>
                {{ __('Goals') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('wearables')" :active="request()->routeIs('wearables')" wire:navigate>
                {{ __('Wearables') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('feedback')" :active="request()->routeIs('feedback')" wire:navigate>
                {{ __('Feedback') }}
            </x-responsive-nav-link>
            @if (auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.model-performance')" :active="request()->routeIs('admin.*')" wire:navigate>
                    {{ __('Admin') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-white/10 pb-1 pt-4">
            <div class="px-4">
                <div class="text-base font-medium text-white" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm font-medium text-slate-400">{{ auth()->user()->email }}</div>
                <div class="mt-1 text-xs uppercase tracking-wide text-teal-300">{{ auth()->user()->role->value }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="confirmLogout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>

    @if ($confirmingLogout)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="logout-title">
            <div class="w-full max-w-sm rounded-2xl border border-white/10 bg-slate-900 p-6 shadow-2xl">
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-400/10 text-amber-300" aria-hidden="true">!</div>
                <h2 id="logout-title" class="mt-4 text-lg font-bold text-white">Sign out of HealthRisk AI?</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">Your saved screening history remains secure. You can sign back in whenever you are ready.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cancelLogout" class="rounded-lg border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-white/5">Stay signed in</button>
                    <button type="button" wire:click="logout" wire:loading.attr="disabled" class="rounded-lg bg-rose-500 px-4 py-2 text-sm font-bold text-white hover:bg-rose-400 disabled:opacity-60">
                        <span wire:loading.remove>Sign out</span><span wire:loading>Signing out...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</nav>
