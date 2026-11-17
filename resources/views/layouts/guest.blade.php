<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-[99vh] bg-slate-950 px-4 py-3 sm:px-6 lg:flex lg:items-center lg:justify-center">
            <div class="w-full max-w-6xl">
                <a href="/" wire:navigate class="mb-3 inline-flex items-center gap-3 text-white">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-400 text-lg font-bold text-slate-950">H</span>
                    <span class="text-lg font-bold tracking-tight">HealthRisk AI</span>
                </a>

                <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-2xl shadow-black/30">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
