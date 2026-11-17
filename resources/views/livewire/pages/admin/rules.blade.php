<?php

use App\Models\RiskRule;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public string $search = '';
    public ?int $editingId = null;
    public ?float $threshold = null;
    public int $points = 0;
    public bool $active = true;

    public function edit(int $id): void
    {
        $rule = RiskRule::findOrFail($id);
        $this->editingId = $id;
        $this->threshold = $rule->threshold;
        $this->points = $rule->points;
        $this->active = $rule->active;
    }

    public function save(): void
    {
        $this->validate(['threshold' => ['nullable', 'numeric'], 'points' => ['required', 'integer', 'min:0', 'max:100'], 'active' => ['boolean']]);
        RiskRule::findOrFail($this->editingId)->update(['threshold' => $this->threshold, 'points' => $this->points, 'active' => $this->active]);
        $this->reset('editingId', 'threshold', 'points');
        $this->active = true;
    }

    public function cancel(): void
    {
        $this->reset('editingId', 'threshold', 'points');
        $this->active = true;
    }

    public function with(): array
    {
        return ['rules' => RiskRule::query()->when($this->search, fn ($query) => $query->where('label', 'like', '%'.$this->search.'%')->orWhere('code', 'like', '%'.$this->search.'%'))->orderBy('label')->get()];
    }
}; ?>

<?php layout('layouts.app'); ?>
<div class="min-h-screen bg-slate-950 lg:grid lg:grid-cols-[210px_1fr]"><x-admin.sidebar active="rules" /><main class="min-w-0"><div class="border-b border-white/10 bg-slate-900/70 px-4 py-3 sm:px-6 lg:px-8"><p class="text-sm font-semibold text-slate-300">Risk rules</p></div><div class="mx-auto max-w-[1200px] space-y-5 px-4 py-6 sm:px-6 lg:px-8"><div><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Assessment engine</p><h1 class="mt-2 text-3xl font-bold text-white">Risk rules</h1><p class="mt-1 text-sm text-slate-400">Adjust thresholds and severity inputs for future assessments.</p></div><input wire:model.live="search" type="search" placeholder="Search rule label or code..." class="w-full max-w-md rounded-lg border-white/10 bg-slate-900 text-sm text-white placeholder-slate-500"><div class="grid gap-4 lg:grid-cols-[1.4fr_1fr]"><section class="space-y-3">@forelse ($rules as $rule)<article class="rounded-xl border border-white/10 bg-slate-900 p-5"><div class="flex items-start justify-between"><div><p class="text-xs text-slate-500">{{ $rule->code }}</p><h2 class="mt-1 font-bold text-white">{{ $rule->label }}</h2></div><span class="text-xs {{ $rule->active ? 'text-teal-300' : 'text-slate-500' }}">{{ $rule->active ? 'Active' : 'Inactive' }}</span></div><p class="mt-3 text-sm text-slate-400">Threshold: {{ $rule->threshold ?? '—' }} · Points: {{ $rule->points }} · Severity: {{ $rule->severity->value }}</p><button wire:click="edit({{ $rule->id }})" class="mt-4 text-sm font-semibold text-teal-300">Edit rule &rarr;</button></article>@empty<p class="rounded-xl border border-white/10 bg-slate-900 p-5 text-sm text-slate-400">No rules found.</p>@endforelse</section><section class="rounded-xl border border-white/10 bg-slate-900 p-5">@if ($editingId)<h2 class="font-bold text-white">Edit rule</h2><form wire:submit="save" class="mt-4 space-y-4"><div><x-input-label for="threshold" value="Threshold" class="text-slate-300" /><x-text-input wire:model="threshold" id="threshold" type="number" step="0.01" class="mt-1 block w-full bg-slate-800 text-white" /></div><div><x-input-label for="points" value="Points" class="text-slate-300" /><x-text-input wire:model="points" id="points" type="number" class="mt-1 block w-full bg-slate-800 text-white" /></div><label class="flex items-center gap-2 text-sm text-slate-300"><input wire:model="active" type="checkbox" class="rounded text-teal-500"> Active</label><div class="flex gap-2"><x-primary-button>Save changes</x-primary-button><button type="button" wire:click="cancel" class="rounded-lg border border-white/10 px-4 py-2 text-sm text-slate-300">Cancel</button></div></form>@else<p class="text-sm leading-6 text-slate-400">Select a rule to edit its threshold, points, or activation status.</p>@endif</section></div></div></main></div>
