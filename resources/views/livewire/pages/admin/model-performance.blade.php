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

<div class="mx-auto max-w-6xl space-y-6 px-4 py-8 sm:px-6">
    <div><h2 class="text-2xl font-semibold text-gray-900">Model Performance</h2><p class="mt-1 text-base text-gray-600">Training and comparison metrics for the served screening models.</p></div>
    <div class="grid gap-4 md:grid-cols-3"><div class="rounded-lg bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Dataset size</p><p class="mt-2 text-2xl font-semibold">{{ number_format($metrics['dataset_size'] ?? 0) }}</p></div><div class="rounded-lg bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Training date</p><p class="mt-2 text-2xl font-semibold">{{ $metrics['training_date'] ?? 'Unknown' }}</p></div><div class="rounded-lg bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Data mode</p><p class="mt-2 text-2xl font-semibold">{{ ($metrics['synthetic'] ?? false) ? 'Synthetic demo' : 'Reference data' }}</p></div></div>
    @foreach ($metrics['conditions'] ?? [] as $condition => $data)
        <section class="rounded-lg bg-white p-6 shadow-sm"><div class="flex flex-wrap items-center justify-between gap-3"><h3 class="text-xl font-semibold capitalize">{{ $condition }}</h3><span class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-800">Served: {{ str_replace('_', ' ', $data['served_model']) }}</span></div><div class="mt-4 overflow-x-auto"><table class="min-w-full text-left text-base"><thead class="border-b text-sm uppercase text-gray-500"><tr><th class="py-2 pr-6">Model</th><th class="py-2 pr-6">ROC AUC</th><th class="py-2 pr-6">PR AUC</th><th class="py-2 pr-6">F1</th><th class="py-2">Recall</th></tr></thead><tbody>@foreach ($data['models'] as $model => $scores)<tr class="border-b last:border-0"><td class="py-3 pr-6">{{ str_replace('_', ' ', $model) }}</td><td class="py-3 pr-6">{{ number_format($scores['roc_auc'], 3) }}</td><td class="py-3 pr-6">{{ number_format($scores['pr_auc'], 3) }}</td><td class="py-3 pr-6">{{ number_format($scores['f1'], 3) }}</td><td class="py-3">{{ number_format($scores['recall'], 3) }}</td></tr>@endforeach</tbody></table></div></section>
    @endforeach
</div>
