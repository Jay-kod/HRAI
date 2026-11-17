<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public array $metrics = [];

    public function mount(): void
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        $path = base_path('ml/artifacts/metrics.json');
        abort_unless(is_file($path), 503, 'Model metrics are not available.');
        $metrics = json_decode(file_get_contents($path), true);
        abort_unless(is_array($metrics), 503, 'Model metrics are invalid.');
        $this->metrics = $metrics;
    }
}; ?>

<?php layout('layouts.app'); ?>

<div class="min-h-screen bg-slate-950 lg:grid lg:grid-cols-[210px_1fr]">
<x-admin.sidebar active="model" />
<main class="min-w-0">
<div class="border-b border-white/10 bg-slate-900/70 px-4 py-3 sm:px-6 lg:px-8"><p class="text-sm font-semibold text-slate-300">Admin dashboard</p></div>
<div class="mx-auto max-w-[1500px] space-y-4 px-4 py-6 sm:px-6 lg:px-8">
    <div><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Administrator workspace</p><h2 class="mt-2 text-3xl font-bold text-white">Model Performance</h2><p class="mt-1 text-sm text-slate-400">Training and comparison metrics for the served screening models.</p></div>
    <div class="grid gap-3 md:grid-cols-3"><div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-lg"><p class="text-xs text-slate-400">Dataset size</p><p class="mt-2 text-2xl font-bold text-white">{{ number_format($metrics['dataset_size'] ?? 0) }}</p></div><div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-lg"><p class="text-xs text-slate-400">Training date</p><p class="mt-2 text-2xl font-bold text-white">{{ $metrics['training_date'] ?? 'Unknown' }}</p></div><div class="rounded-xl border border-white/10 bg-slate-900 p-4 shadow-lg"><p class="text-xs text-slate-400">Data mode</p><p class="mt-2 text-2xl font-bold text-amber-300">{{ ($metrics['synthetic'] ?? false) ? 'Synthetic demo' : 'Reference data' }}</p></div></div>
    @foreach ($metrics['conditions'] ?? [] as $condition => $data)
        <section class="overflow-hidden rounded-xl border border-white/10 bg-slate-900 shadow-lg"><div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-5 py-4"><h3 class="text-lg font-bold capitalize text-white">{{ $condition }}</h3><span class="rounded-full bg-teal-400/10 px-3 py-1 text-xs font-semibold text-teal-300">Served: {{ str_replace('_', ' ', $data['served_model']) }}</span></div><div class="overflow-x-auto p-5"><table class="min-w-full text-left text-sm"><thead class="border-b border-white/10 text-xs uppercase tracking-wide text-slate-500"><tr><th class="py-2 pr-6">Model</th><th class="py-2 pr-6">ROC AUC</th><th class="py-2 pr-6">PR AUC</th><th class="py-2 pr-6">F1</th><th class="py-2">Recall</th></tr></thead><tbody class="text-slate-300">@foreach ($data['models'] as $model => $scores)<tr class="border-b border-white/10 last:border-0"><td class="py-3 pr-6 font-medium text-white">{{ str_replace('_', ' ', $model) }}</td><td class="py-3 pr-6">{{ number_format($scores['roc_auc'], 3) }}</td><td class="py-3 pr-6">{{ number_format($scores['pr_auc'], 3) }}</td><td class="py-3 pr-6">{{ number_format($scores['f1'], 3) }}</td><td class="py-3">{{ number_format($scores['recall'], 3) }}</td></tr>@endforeach</tbody></table></div></section>
    @endforeach
</div>
</main>
</div>
