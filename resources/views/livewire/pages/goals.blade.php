<?php

use App\Actions\CreateHealthGoal;
use App\Enums\GoalMetric;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public string $metric = 'weight_kg';
    public ?float $target = null;
    public ?float $start_value = null;
    public ?int $confirmingGoalId = null;

    public function createGoal(CreateHealthGoal $createHealthGoal): void
    {
        $validated = $this->validate([
            'metric' => ['required', 'in:weight_kg,bmi,systolic,fasting_glucose_mgdl,resting_heart_rate'],
            'target' => ['required', 'numeric', 'between:1,1000'],
            'start_value' => ['nullable', 'numeric', 'between:1,1000'],
        ]);

        $createHealthGoal->execute(Auth::user(), $validated);
        $this->reset('target', 'start_value');
        $this->dispatch('goal-created');
    }

    public function abandon(int $goalId): void
    {
        $goal = Auth::user()->healthGoals()->findOrFail($goalId);
        $this->authorize('update', $goal);
        $goal->update(['status' => 'abandoned', 'abandoned_at' => now()]);
    }

    public function confirmAbandon(int $goalId): void
    {
        $this->confirmingGoalId = $goalId;
    }

    public function cancelAbandon(): void
    {
        $this->confirmingGoalId = null;
    }

    public function abandonConfirmed(): void
    {
        if ($this->confirmingGoalId === null) {
            return;
        }

        $goalId = $this->confirmingGoalId;
        $this->confirmingGoalId = null;
        $this->abandon($goalId);
    }

    public function with(): array
    {
        return ['goals' => Auth::user()->healthGoals()->latest()->get()];
    }
}; ?>

<?php layout('layouts.app'); ?>

<div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
    <h2 class="text-2xl font-semibold text-gray-900">Health goals</h2>
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.5fr)]">
        <form wire:submit="createGoal" class="space-y-5 rounded-lg bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Create a goal</h3>
            <div>
                <x-input-label for="metric" value="Metric" />
                <select wire:model="metric" id="metric" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach (GoalMetric::cases() as $option)
                        <option value="{{ $option->value }}">{{ ucfirst(str_replace('_', ' ', $option->value)) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('metric')" class="mt-2" />
            </div>
            <div><x-input-label for="target" value="Target value" /><x-text-input wire:model="target" id="target" type="number" step="0.01" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('target')" class="mt-2" /></div>
            <div><x-input-label for="start_value" value="Starting value (optional)" /><x-text-input wire:model="start_value" id="start_value" type="number" step="0.01" class="mt-1 block w-full" /><x-input-error :messages="$errors->get('start_value')" class="mt-2" /></div>
            <x-primary-button>Create goal</x-primary-button>
            <x-action-message on="goal-created">Goal saved.</x-action-message>
        </form>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold">Your active goals</h3>
            @forelse ($goals as $goal)
                <article class="mt-4 rounded-md border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div><h4 class="font-semibold">{{ ucfirst(str_replace('_', ' ', $goal->metric->value)) }}</h4><p class="mt-1 text-base text-gray-600">Target: {{ $goal->target }} @if ($goal->start_value) · Starting: {{ $goal->start_value }} @endif</p></div>
                        <span class="text-sm font-medium uppercase text-gray-600">{{ $goal->status->value }}</span>
                    </div>
                    @if ($goal->status->value === 'active')
                        <button wire:click="confirmAbandon({{ $goal->id }})" class="mt-3 text-sm font-medium text-red-700 underline">Abandon goal</button>
                    @endif
                </article>
            @empty
                <p class="mt-3 text-base text-gray-600">No goals yet. Create one to track a preventive-care target.</p>
            @endforelse
        </section>
    </div>
    <x-health-disclaimer />

    @if ($confirmingGoalId !== null)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="abandon-goal-title">
            <div class="w-full max-w-sm rounded-2xl border border-rose-200 bg-white p-6 shadow-2xl">
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-rose-100 text-rose-700" aria-hidden="true">!</div>
                <h2 id="abandon-goal-title" class="mt-4 text-lg font-bold text-slate-900">Abandon this goal?</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">This will mark the goal as abandoned. You can create a new goal later.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cancelAbandon" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Keep goal</button>
                    <button type="button" wire:click="abandonConfirmed" wire:loading.attr="disabled" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-bold text-white hover:bg-rose-700 disabled:opacity-60"><span wire:loading.remove>Abandon goal</span><span wire:loading>Updating...</span></button>
                </div>
            </div>
        </div>
    @endif
</div>
