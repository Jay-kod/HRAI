<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-slate-950 text-slate-100">
            <div wire:loading.flex class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/70 px-4 backdrop-blur-sm">
                <div class="w-full max-w-xs rounded-2xl border border-teal-300/20 bg-slate-900 p-5 text-center shadow-2xl">
                    <div class="mx-auto flex h-10 w-10 animate-pulse items-center justify-center rounded-xl bg-teal-400 text-lg font-black text-slate-950">H</div>
                    <p class="mt-3 text-sm font-semibold text-white">Loading your workspace</p>
                    <div class="mt-3 space-y-2" aria-hidden="true"><div class="h-2 animate-pulse rounded bg-white/10"></div><div class="h-2 w-2/3 animate-pulse rounded bg-white/10"></div></div>
                </div>
            </div>
            @auth
                @if (request()->routeIs('dashboard') || request()->routeIs('admin.*'))
                    @if (request()->routeIs('admin.*'))
                        <div class="hidden">
                            <livewire:layout.navigation />
                        </div>
                    @else
                        <x-user.sidebar />
                        <div class="hidden">
                            <livewire:layout.navigation />
                        </div>
                    @endif
                @else
                    <x-user.sidebar />
                    <div class="lg:pl-[210px]">
                        <livewire:layout.navigation />
                    </div>
                @endif
            @endauth

            <!-- Page Heading -->
            @if (isset($header))
                <header class="border-b border-white/10 bg-slate-900 {{ auth()->check() && ! request()->routeIs('admin.*') ? 'lg:ml-[210px]' : '' }}">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="{{ auth()->check() && ! request()->routeIs('admin.*') ? 'lg:ml-[210px]' : '' }}">
                {{ $slot }}
            </main>

            @unless (request()->routeIs('dashboard') || request()->routeIs('admin.*'))
                <footer class="border-t border-white/10 bg-slate-950 px-4 py-6 text-slate-400 sm:px-6 lg:px-8 {{ auth()->check() && ! request()->routeIs('admin.*') ? 'lg:ml-[210px]' : '' }}">
                    <div class="mx-auto max-w-7xl">
                        <x-health-disclaimer />
                    </div>
                </footer>
            @endunless
        </div>
    </body>
</html>
