<?php

use App\Models\ReportShare;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public ReportShare $share;

    public function mount(string $token): void
    {
        $this->share = ReportShare::query()
            ->where('token', $token)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->with(['riskAssessment.recommendations'])
            ->firstOrFail();

        $this->share->update(['last_viewed_at' => now()]);
    }
}; ?>

<?php layout('layouts.app'); ?>

<style>
    @media print { nav, footer { display: none !important; } body { background: white !important; } }
</style>

<div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
    <div class="rounded-lg bg-white p-6 shadow-sm">
        <p class="text-sm text-gray-500">Shared screening report · Expires {{ $share->expires_at->format('j M Y') }}</p>
        <h2 class="mt-1 text-2xl font-semibold">HealthRisk AI screening report</h2>
        <p class="mt-3 text-base text-gray-600">This report was shared for educational discussion.</p>
        <x-health-disclaimer class="mt-4 rounded-md bg-gray-50 p-4" />
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        @foreach ($share->riskAssessment->condition_results as $condition => $result)
            <article class="rounded-lg bg-white p-5 shadow-sm"><h3 class="text-lg font-semibold capitalize">{{ $condition }}</h3><p class="mt-3 text-3xl font-semibold">{{ number_format(((float) $result['probability']) * 100, 1) }}%</p><div class="mt-3"><x-risk-level-badge :level="$result['level']" /></div></article>
        @endforeach
    </div>
    <section class="rounded-lg bg-white p-6 shadow-sm"><h3 class="text-xl font-semibold">Preventive recommendations</h3><ul class="mt-4 space-y-3 text-base">@forelse ($share->riskAssessment->recommendations as $recommendation)<li><strong>{{ $recommendation->title }}:</strong> {{ $recommendation->advice }}</li>@empty<li>No recommendations were generated.</li>@endforelse</ul></section>
</div>
