<?php

use App\Models\RiskAssessment;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public RiskAssessment $assessment;

    public function mount(RiskAssessment $assessment): void
    {
        $this->authorize('view', $assessment);
        $this->assessment = $assessment->load('recommendations');
    }
}; ?>

<?php layout('layouts.app'); ?>

<div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
        <h2 class="text-2xl font-semibold text-gray-900">Screening result</h2>
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <p class="text-sm text-gray-500">{{ $assessment->assessed_at->format('j M Y, H:i') }}</p>
                    <h3 class="mt-1 text-2xl font-semibold text-gray-900">Overall screening estimate</h3>
                    <p class="mt-2 text-base text-gray-600">Engine: {{ $assessment->engine === 'ml' ? 'ML service' : 'Rule-based fallback' }}</p>
                </div>
                <x-risk-level-badge :level="$assessment->overall_level->value" />
            </div>
            <p class="mt-6 text-4xl font-semibold text-gray-900">{{ number_format((float) $assessment->overall_score, 1) }}<span class="text-lg font-normal text-gray-500"> / 100</span></p>
            <x-health-disclaimer class="mt-4 rounded-md bg-gray-50 p-4" />
        </div>

        <section>
            <h3 class="text-xl font-semibold text-gray-900">Condition estimates</h3>
            <div class="mt-4 grid gap-4 md:grid-cols-3">
                @foreach ($assessment->condition_results as $condition => $result)
                    <article class="rounded-lg bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="text-lg font-semibold capitalize">{{ $condition }}</h4>
                            <x-risk-level-badge :level="$result['level']" />
                        </div>
                        <p class="mt-5 text-3xl font-semibold">{{ number_format(((float) $result['probability']) * 100, 1) }}%</p>
                        <p class="mt-1 text-base text-gray-600">Estimated probability</p>
                        <p class="mt-3 text-sm text-gray-500">Percentile: {{ number_format((float) $result['percentile'], 1) }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-gray-900">Factors considered</h3>
            @if (count($assessment->risk_factors ?? []))
                <ul class="mt-4 space-y-3">
                    @foreach ($assessment->risk_factors as $factor)
                        <li class="flex items-start gap-3 text-base"><span class="mt-1 text-amber-600" aria-hidden="true">▲</span><span>{{ $factor['label'] }}</span></li>
                    @endforeach
                </ul>
            @else
                <p class="mt-3 text-base text-gray-600">No additional rule-based factors were triggered by this check-in.</p>
            @endif
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-gray-900">Preventive recommendations</h3>
            @if ($assessment->recommendations->isNotEmpty())
                <div class="mt-4 space-y-4">
                    @foreach ($assessment->recommendations as $recommendation)
                        <article class="border-l-4 border-indigo-500 pl-4">
                            <h4 class="font-semibold">{{ $recommendation->title }}</h4>
                            <p class="mt-1 text-base text-gray-700">{{ $recommendation->advice }}</p>
                            <p class="mt-1 text-sm text-gray-500">Why this may help: {{ $recommendation->rationale }}</p>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="mt-3 text-base text-gray-600">No recommendations were generated for this screening.</p>
            @endif
        </section>

        <div class="flex flex-wrap gap-3">
            <x-primary-button-link :href="route('check-in')" wire:navigate>Start another check-in</x-primary-button-link>
            <x-secondary-button-link :href="route('history')" wire:navigate>View history</x-secondary-button-link>
        </div>
</div>
