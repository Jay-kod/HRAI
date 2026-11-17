<x-app-layout>
    @php
        $users = \App\Models\User::count();
        $activeUsers = \App\Models\User::where('active', true)->count();
        $assessments = \App\Models\RiskAssessment::count();
        $feedback = \App\Models\UsabilityFeedback::count();
        $riskCounts = collect(\App\Enums\RiskLevel::cases())->mapWithKeys(
            fn ($level) => [$level->value => \App\Models\RiskAssessment::where('overall_level', $level->value)->count()]
        );
        $mlHealthy = false;
        try {
            $mlHealthy = \Illuminate\Support\Facades\Http::timeout(1)->get(config('services.ml.url').'/health')->successful();
        } catch (\Illuminate\Http\Client\ConnectionException) {
            $mlHealthy = false;
        }
    @endphp
    <div class="min-h-screen bg-slate-950 lg:grid lg:grid-cols-[210px_1fr]">
        <x-admin.sidebar active="overview" />
        <main class="min-w-0">
            <div class="border-b border-white/10 bg-slate-900/70 px-4 py-3 sm:px-6 lg:px-8"><p class="text-sm font-semibold text-slate-300">Admin overview</p></div>
            <div class="mx-auto max-w-[1500px] space-y-5 px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Administrator workspace</p><h1 class="mt-2 text-3xl font-bold text-white">System overview</h1><p class="mt-1 text-sm text-slate-400">Monitor adoption, screening activity, and service health.</p></div>
                    <a href="{{ route('admin.model') }}" wire:navigate class="rounded-lg bg-amber-300 px-4 py-2.5 text-sm font-bold text-slate-950 hover:bg-amber-200">Review model evidence &rarr;</a>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-xl border border-white/10 bg-slate-900 p-4"><p class="text-xs text-slate-400">Total users</p><p class="mt-2 text-3xl font-bold text-white">{{ number_format($users) }}</p><p class="mt-2 text-xs text-teal-300">{{ number_format($activeUsers) }} active accounts</p></div>
                    <div class="rounded-xl border border-white/10 bg-slate-900 p-4"><p class="text-xs text-slate-400">Assessments</p><p class="mt-2 text-3xl font-bold text-white">{{ number_format($assessments) }}</p><p class="mt-2 text-xs text-slate-500">Screening records</p></div>
                    <div class="rounded-xl border border-white/10 bg-slate-900 p-4"><p class="text-xs text-slate-400">Feedback responses</p><p class="mt-2 text-3xl font-bold text-white">{{ number_format($feedback) }}</p><p class="mt-2 text-xs text-slate-500">Usability signals</p></div>
                    <div class="rounded-xl border border-white/10 bg-slate-900 p-4"><p class="text-xs text-slate-400">ML service</p><p class="mt-2 text-2xl font-bold {{ $mlHealthy ? 'text-teal-300' : 'text-rose-300' }}">{{ $mlHealthy ? 'Online' : 'Offline' }}</p><p class="mt-2 text-xs text-slate-500">{{ $mlHealthy ? 'Predictions available' : 'Rule fallback available' }}</p></div>
                </div>

                <div class="grid gap-4 xl:grid-cols-[1.2fr_0.8fr]">
                    <section class="rounded-xl border border-white/10 bg-slate-900">
                        <div class="border-b border-white/10 px-5 py-4"><h2 class="text-base font-bold text-white">Risk distribution</h2><p class="mt-1 text-xs text-slate-400">Overall assessment levels across the system.</p></div>
                        <div class="relative h-64 p-5">
                            @if ($assessments > 0)
                                <canvas id="risk-distribution-chart" aria-label="Overall assessment risk distribution" data-labels="{{ $riskCounts->keys()->map(fn ($level) => ucfirst($level))->values()->toJson() }}" data-values="{{ $riskCounts->values()->toJson() }}"></canvas>
                            @else
                                <div class="flex h-full items-center justify-center rounded-lg border border-dashed border-white/10 text-sm text-slate-500">Risk distribution appears after the first assessment.</div>
                            @endif
                        </div>
                    </section>
                    <section class="rounded-xl border border-white/10 bg-slate-900 p-5">
                        <h2 class="text-base font-bold text-white">Administration</h2>
                        <p class="mt-1 text-xs text-slate-400">Manage the system evidence and content.</p>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('admin.templates') }}" wire:navigate class="flex items-center justify-between rounded-lg bg-white/5 p-3 text-sm text-slate-200 hover:bg-white/10">Recommendation templates <span class="text-teal-300">&rarr;</span></a>
                            <a href="{{ route('admin.rules') }}" wire:navigate class="flex items-center justify-between rounded-lg bg-white/5 p-3 text-sm text-slate-200 hover:bg-white/10">Risk rules <span class="text-teal-300">&rarr;</span></a>
                            <a href="{{ route('admin.users') }}" wire:navigate class="flex items-center justify-between rounded-lg bg-white/5 p-3 text-sm text-slate-200 hover:bg-white/10">Users <span class="text-teal-300">&rarr;</span></a>
                            <a href="{{ route('admin.feedback') }}" wire:navigate class="flex items-center justify-between rounded-lg bg-white/5 p-3 text-sm text-slate-200 hover:bg-white/10">Usability feedback <span class="text-teal-300">&rarr;</span></a>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
