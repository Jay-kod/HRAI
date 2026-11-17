<?php

use App\Actions\CreateReportShare;
use App\Models\RiskAssessment;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public RiskAssessment $assessment;
    public ?string $shareUrl = null;

    public function mount(RiskAssessment $assessment): void
    {
        $this->authorize('view', $assessment);
        $this->assessment = $assessment->load('recommendations');
    }

    public function createShare(CreateReportShare $createReportShare): void
    {
        $share = $createReportShare->execute(Auth::user(), $this->assessment);
        $this->shareUrl = route('shared.show', ['token' => $share->token]);
    }
}; ?>

<?php layout('layouts.app'); ?>

<style>
    @media print {
        nav, footer, .no-print { display: none !important; }
        body { background: white !important; }
    }
</style>

<div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
    <div class="no-print flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-2xl font-semibold text-gray-900">Printable health report</h2>
        <div class="flex gap-3"><x-primary-button type="button" onclick="window.print()">Print report</x-primary-button><x-secondary-button type="button" wire:click="createShare">Create seven-day share link</x-secondary-button></div>
    </div>
    @if ($shareUrl)
        <div class="no-print rounded-md border border-green-300 bg-green-50 p-4 text-base text-green-900">Share link created: <a class="break-all underline" href="{{ $shareUrl }}">{{ $shareUrl }}</a></div>
    @endif
    <div class="rounded-lg bg-white p-6 shadow-sm print:shadow-none">
        <p class="text-sm text-gray-500">Assessment date: {{ $assessment->assessed_at->format('j M Y, H:i') }}</p>
        <h3 class="mt-1 text-2xl font-semibold">HealthRisk AI screening report</h3>
        <div class="mt-5 flex items-center gap-3"><span class="text-lg font-medium">Overall estimate: {{ number_format((float) $assessment->overall_score, 1) }} / 100</span><x-risk-level-badge :level="$assessment->overall_level->value" /></div>
        <x-health-disclaimer class="mt-4 rounded-md bg-gray-50 p-4" />
    </div>
    <section class="grid gap-4 md:grid-cols-3">
        @foreach ($assessment->condition_results as $condition => $result)
            <article class="rounded-lg bg-white p-5 shadow-sm print:shadow-none"><h4 class="text-lg font-semibold capitalize">{{ $condition }}</h4><p class="mt-3 text-3xl font-semibold">{{ number_format(((float) $result['probability']) * 100, 1) }}%</p><p class="mt-1 text-base text-gray-600">Estimated probability</p><div class="mt-3"><x-risk-level-badge :level="$result['level']" /></div></article>
        @endforeach
    </section>
    <section class="rounded-lg bg-white p-6 shadow-sm print:shadow-none"><h3 class="text-xl font-semibold">Factors and recommendations</h3><div class="mt-4 grid gap-6 md:grid-cols-2"><ul class="space-y-2 text-base">@forelse ($assessment->risk_factors ?? [] as $factor)<li>▲ {{ $factor['label'] }}</li>@empty<li>No additional factors were triggered.</li>@endforelse</ul><ul class="space-y-3 text-base">@forelse ($assessment->recommendations as $recommendation)<li><strong>{{ $recommendation->title }}:</strong> {{ $recommendation->advice }}</li>@empty<li>No recommendations were generated.</li>@endforelse</ul></div></section>
</div>
