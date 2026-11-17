<?php

use App\Services\Ml\MlControlService;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public array $health = [];
    public array $dataset = [];
    public ?array $prediction = null;
    public bool $testing = false;

    public function mount(MlControlService $service): void
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        $this->health = $service->health();
        $this->dataset = $service->datasetSummary();
    }

    public function refreshHealth(MlControlService $service): void
    {
        $this->health = $service->health();
        $this->dataset = $service->datasetSummary();
        $this->prediction = null;
    }

    public function runTestPrediction(MlControlService $service): void
    {
        $this->testing = true;
        $this->prediction = $service->testPrediction();
        $this->testing = false;
    }
}; ?>

<?php layout('layouts.app'); ?>

<div class="min-h-screen bg-slate-950 lg:grid lg:grid-cols-[210px_1fr]">
    <x-admin.sidebar active="ai" />
    <main class="min-w-0">
        <div class="border-b border-white/10 bg-slate-900/70 px-4 py-3 sm:px-6 lg:px-8"><p class="text-sm font-semibold text-slate-300">AI control</p></div>
        <div class="mx-auto max-w-[1200px] space-y-5 px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Administrator workspace</p><h1 class="mt-2 text-3xl font-bold text-white">AI Control Center</h1><p class="mt-1 text-sm text-slate-400">Monitor the local model service that powers screening predictions.</p></div>
                <button wire:click="refreshHealth" wire:loading.attr="disabled" class="rounded-lg border border-white/10 px-4 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/5 disabled:opacity-60"><span wire:loading.remove>Refresh status</span><span wire:loading>Checking...</span></button>
            </div>

            <section class="rounded-xl border {{ ($health['online'] ?? false) ? 'border-teal-400/30 bg-teal-400/5' : 'border-rose-400/30 bg-rose-400/5' }} p-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div><p class="text-xs font-bold uppercase tracking-widest {{ ($health['online'] ?? false) ? 'text-teal-300' : 'text-rose-300' }}">Connection status</p><h2 class="mt-2 text-2xl font-bold text-white">{{ ($health['online'] ?? false) ? 'AI service is online' : 'AI service is offline or degraded' }}</h2><p class="mt-2 max-w-2xl text-sm text-slate-300">{{ $health['message'] ?? 'Status has not been checked yet.' }}</p></div>
                    <span class="rounded-full {{ ($health['online'] ?? false) ? 'bg-teal-400/15 text-teal-200' : 'bg-rose-400/15 text-rose-200' }} px-3 py-1 text-xs font-bold">{{ ($health['online'] ?? false) ? 'READY' : 'FALLBACK ACTIVE' }}</span>
                </div>
            </section>

            <div class="grid gap-4 lg:grid-cols-2">
                <section class="rounded-xl border border-white/10 bg-slate-900 p-5">
                    <h2 class="text-base font-bold text-white">Model connection</h2>
                    <dl class="mt-4 divide-y divide-white/10 text-sm">
                        <div class="flex justify-between gap-4 py-3"><dt class="text-slate-500">Endpoint</dt><dd class="text-right font-mono text-xs text-slate-300">{{ config('services.ml.url') }}</dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt class="text-slate-500">Available conditions</dt><dd class="text-slate-300">{{ implode(', ', $health['payload']['conditions'] ?? []) ?: 'Unavailable' }}</dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt class="text-slate-500">Data mode</dt><dd class="text-slate-300">{{ ($health['payload']['synthetic'] ?? false) ? 'Synthetic demo data' : 'Reference data' }}</dd></div>
                    </dl>
                </section>
                <section class="rounded-xl border border-white/10 bg-slate-900 p-5">
                    <h2 class="text-base font-bold text-white">Test prediction</h2>
                    <p class="mt-1 text-sm text-slate-400">Send the documented sample feature set through the live endpoint.</p>
                    <button wire:click="runTestPrediction" wire:loading.attr="disabled" class="mt-5 rounded-lg bg-amber-300 px-4 py-2.5 text-sm font-bold text-slate-950 hover:bg-amber-200 disabled:opacity-60"><span wire:loading.remove>Run test prediction</span><span wire:loading>Running...</span></button>
                    @if ($prediction)
                        <div class="mt-5 grid grid-cols-3 gap-2">
                            @foreach ($prediction as $condition => $result)
                                <div class="rounded-lg border border-white/10 bg-white/5 p-3"><p class="text-xs capitalize text-slate-400">{{ $condition }}</p><p class="mt-2 text-lg font-bold text-white">{{ number_format((float) ($result['probability'] ?? 0) * 100, 1) }}%</p><p class="mt-1 text-xs capitalize text-teal-300">{{ $result['level'] ?? 'unknown' }}</p></div>
                            @endforeach
                        </div>
                    @elseif ($testing)
                        <p class="mt-4 text-sm text-slate-400">Waiting for the local model service...</p>
                    @endif
                </section>
            </div>

            <section class="rounded-xl border border-white/10 bg-slate-900 p-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div><p class="text-xs font-bold uppercase tracking-widest text-amber-300">Training data</p><h2 class="mt-2 text-xl font-bold text-white">Dataset overview</h2><p class="mt-1 text-sm text-slate-400">Inspect the data currently represented by the trained artifacts before improving or updating the model.</p></div>
                    <span class="rounded-full {{ $dataset['synthetic'] ?? false ? 'bg-amber-300/15 text-amber-200' : 'bg-teal-400/15 text-teal-200' }} px-3 py-1 text-xs font-bold">{{ ($dataset['synthetic'] ?? false) ? 'SYNTHETIC FALLBACK' : 'REFERENCE DATA' }}</span>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><p class="text-xs text-slate-500">Source</p><p class="mt-2 text-sm font-semibold text-slate-200">{{ $dataset['source'] ?? 'Unknown' }}</p></div>
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><p class="text-xs text-slate-500">Rows used for training</p><p class="mt-2 text-xl font-bold text-white">{{ number_format($dataset['rows'] ?? 0) }}</p></div>
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><p class="text-xs text-slate-500">Last training date</p><p class="mt-2 text-sm font-semibold text-slate-200">{{ $dataset['trained_at'] ?? 'Unknown' }}</p></div>
                </div>
                <p class="mt-4 rounded-lg border border-amber-300/20 bg-amber-300/5 p-3 text-sm text-amber-100/80">{{ $dataset['note'] ?? 'Dataset status is unavailable.' }}</p>
                <div class="mt-4"><p class="text-xs font-bold uppercase tracking-widest text-slate-500">Model features</p><div class="mt-2 flex flex-wrap gap-2">@foreach ($dataset['features'] ?? [] as $feature)<span class="rounded-md bg-white/5 px-2 py-1 font-mono text-xs text-slate-300">{{ $feature }}</span>@endforeach</div></div>
            </section>

            <div class="rounded-xl border border-amber-300/20 bg-amber-300/5 p-5 text-sm text-amber-100/80">The application uses this local model for educational screening estimates. If the service is unavailable, the assessment pipeline automatically uses its documented rule-based fallback. This page does not expose credentials or allow arbitrary model changes.</div>
        </div>
    </main>
</div>
