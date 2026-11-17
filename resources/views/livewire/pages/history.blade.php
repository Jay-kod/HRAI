<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public function with(): array
    {
        return [
            'assessments' => Auth::user()->riskAssessments()->latest('assessed_at')->paginate(10),
        ];
    }
}; ?>

<?php layout('layouts.app'); ?>

<div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
        <h2 class="text-2xl font-semibold text-gray-900">Assessment history</h2>
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <h3 class="text-xl font-semibold">Your previous screenings</h3>
            @if ($assessments->isEmpty())
                <p class="mt-3 text-base text-gray-600">No assessments yet. Start your first check-in when you are ready.</p>
                <x-primary-button-link :href="route('check-in')" class="mt-4" wire:navigate>Start check-in</x-primary-button-link>
            @else
                <div class="mt-4 divide-y divide-gray-200">
                    @foreach ($assessments as $assessment)
                        <a href="{{ route('assessments.show', $assessment) }}" wire:navigate class="flex flex-col gap-3 py-4 hover:bg-gray-50 sm:flex-row sm:items-center sm:justify-between">
                            <div><p class="font-semibold text-gray-900">{{ $assessment->assessed_at->format('j M Y, H:i') }}</p><p class="text-base text-gray-600">{{ ucfirst($assessment->engine) }} screening</p></div>
                            <x-risk-level-badge :level="$assessment->overall_level->value" />
                        </a>
                    @endforeach
                </div>
                <div class="mt-4">{{ $assessments->links() }}</div>
            @endif
        </div>
        <x-health-disclaimer />
</div>
