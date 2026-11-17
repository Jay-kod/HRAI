<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HealthRisk AI</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        <main class="mx-auto flex min-h-screen max-w-6xl flex-col justify-center gap-10 px-6 py-12 lg:flex-row lg:items-center">
            <section class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-700">HealthRisk AI</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight sm:text-5xl">Understand your health risks and plan preventive action.</h1>
                <p class="mt-6 text-lg leading-8 text-slate-600">Complete a guided health check-in, review educational screening estimates for heart disease, diabetes, and hypertension, and track practical preventive goals.</p>
                <p class="mt-5 text-base leading-7 text-slate-600">This is an educational screening prototype, not a medical diagnosis or replacement for professional healthcare advice.</p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('login') }}" class="rounded-md bg-indigo-700 px-5 py-3 text-base font-semibold text-white hover:bg-indigo-800">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-md border border-slate-300 bg-white px-5 py-3 text-base font-semibold text-slate-800 hover:bg-slate-100">Create account</a>
                </div>
            </section>
            <section class="grid w-full max-w-md gap-4 sm:grid-cols-3 lg:grid-cols-1">
                @foreach (['Guided check-in' => 'Measurements, lifestyle, and history in four clear stages.', 'Explainable results' => 'Risk factors and recommendations are shown alongside each estimate.', 'Progress tracking' => 'Save assessments, goals, and simulated wearable trends.'] as $title => $description)
                    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="text-lg font-semibold">{{ $title }}</h2>
                        <p class="mt-2 text-base leading-6 text-slate-600">{{ $description }}</p>
                    </article>
                @endforeach
            </section>
        </main>
    </body>
</html>
