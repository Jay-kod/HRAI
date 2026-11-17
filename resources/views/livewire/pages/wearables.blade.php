<?php

use App\Services\WearableSimulator;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public function simulate(WearableSimulator $simulator): void
    {
        $created = $simulator->simulate(Auth::user());
        $this->dispatch('wearables-imported', count: $created);
    }

    public function with(): array
    {
        return ['sessions' => Auth::user()->wearableSessions()->latest('session_date')->limit(14)->get()];
    }
}; ?>

<?php layout('layouts.app'); ?>

<div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><h2 class="text-2xl font-semibold text-gray-900">Wearable activity</h2><p class="mt-1 text-base text-gray-600">Use simulated sessions to demonstrate trend data without connecting a device.</p></div>
        <x-primary-button wire:click="simulate">Generate seven days</x-primary-button>
    </div>
    <x-action-message on="wearables-imported">Simulation data updated.</x-action-message>
    <section class="overflow-hidden rounded-lg bg-white shadow-sm">
        @if ($sessions->isEmpty())
            <p class="p-6 text-base text-gray-600">No wearable sessions yet.</p>
        @else
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-left text-base"><thead class="bg-gray-50 text-sm uppercase text-gray-500"><tr><th class="px-6 py-3">Date</th><th class="px-6 py-3">Steps</th><th class="px-6 py-3">Avg heart rate</th><th class="px-6 py-3">Sleep</th></tr></thead><tbody class="divide-y divide-gray-200">@foreach ($sessions as $session)<tr><td class="whitespace-nowrap px-6 py-4">{{ $session->session_date->format('j M Y') }}</td><td class="px-6 py-4">{{ number_format($session->steps) }}</td><td class="px-6 py-4">{{ $session->average_heart_rate }} bpm</td><td class="px-6 py-4">{{ $session->sleep_hours }} hours</td></tr>@endforeach</tbody></table></div>
        @endif
    </section>
    <x-health-disclaimer />
</div>
