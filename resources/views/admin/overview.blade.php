<x-app-layout>
    @php
        $users = \App\Models\User::count();
        $assessments = \App\Models\RiskAssessment::count();
        $feedback = \App\Models\UsabilityFeedback::count();
        $mlHealthy = \Illuminate\Support\Facades\Http::timeout(1)->get(config('services.ml.url').'/health')->successful();
    @endphp
    <div class="min-h-screen bg-slate-950 lg:grid lg:grid-cols-[210px_1fr]">
        <x-admin.sidebar active="overview" />
        <main class="min-w-0">
            <div class="border-b border-white/10 bg-slate-900/70 px-4 py-3 sm:px-6 lg:px-8"><p class="text-sm font-semibold text-slate-300">Admin overview</p></div>
            <div class="mx-auto max-w-[1500px] space-y-5 px-4 py-6 sm:px-6 lg:px-8">
                <div><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Administrator workspace</p><h1 class="mt-2 text-3xl font-bold text-white">System overview</h1><p class="mt-1 text-sm text-slate-400">Monitor adoption, screening activity, and service health.</p></div>
                <div class="grid gap-3 md:grid-cols-4">
                    <div class="rounded-xl border border-white/10 bg-slate-900 p-4"><p class="text-xs text-slate-400">Users</p><p class="mt-2 text-3xl font-bold text-white">{{ number_format($users) }}</p></div>
                    <div class="rounded-xl border border-white/10 bg-slate-900 p-4"><p class="text-xs text-slate-400">Assessments</p><p class="mt-2 text-3xl font-bold text-white">{{ number_format($assessments) }}</p></div>
                    <div class="rounded-xl border border-white/10 bg-slate-900 p-4"><p class="text-xs text-slate-400">Feedback responses</p><p class="mt-2 text-3xl font-bold text-white">{{ number_format($feedback) }}</p></div>
                    <div class="rounded-xl border border-white/10 bg-slate-900 p-4"><p class="text-xs text-slate-400">ML service</p><p class="mt-2 text-2xl font-bold {{ $mlHealthy ? 'text-teal-300' : 'text-rose-300' }}">{{ $mlHealthy ? 'Online' : 'Offline' }}</p></div>
                </div>
                <div class="grid gap-4 lg:grid-cols-2">
                    <section class="rounded-xl border border-white/10 bg-slate-900 p-5"><h2 class="text-base font-bold text-white">Administration</h2><div class="mt-4 grid gap-2 sm:grid-cols-2"><a href="{{ route('admin.model') }}" wire:navigate class="rounded-lg bg-white/5 p-3 text-sm text-slate-200 hover:bg-white/10">Model performance <span class="float-right text-teal-300">&rarr;</span></a><a href="{{ route('admin.templates') }}" wire:navigate class="rounded-lg bg-white/5 p-3 text-sm text-slate-200 hover:bg-white/10">Recommendation templates <span class="float-right text-teal-300">&rarr;</span></a><a href="{{ route('admin.rules') }}" wire:navigate class="rounded-lg bg-white/5 p-3 text-sm text-slate-200 hover:bg-white/10">Risk rules <span class="float-right text-teal-300">&rarr;</span></a><a href="{{ route('admin.users') }}" wire:navigate class="rounded-lg bg-white/5 p-3 text-sm text-slate-200 hover:bg-white/10">Users <span class="float-right text-teal-300">&rarr;</span></a></div></section>
                    <section class="rounded-xl border border-white/10 bg-slate-900 p-5"><h2 class="text-base font-bold text-white">Risk distribution</h2><p class="mt-1 text-xs text-slate-400">Current assessment levels across the system.</p><div class="mt-5 space-y-3">@foreach (\App\Enums\RiskLevel::cases() as $level)<div><div class="flex justify-between text-xs"><span class="text-slate-300">{{ ucfirst($level->value) }}</span><span class="text-slate-500">{{ \App\Models\RiskAssessment::where('overall_level', $level->value)->count() }}</span></div><div class="mt-1 h-2 rounded-full bg-slate-800"><div class="h-full rounded-full bg-teal-400" style="width: {{ $assessments ? min(100, \App\Models\RiskAssessment::where('overall_level', $level->value)->count() / $assessments * 100) : 0 }}%"></div></div></div>@endforeach</div></section>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
