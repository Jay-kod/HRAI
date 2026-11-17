<?php

use App\Models\RecommendationTemplate;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public string $search = '';
    public ?int $editingId = null;
    public string $title = '';
    public string $body = '';
    public bool $active = true;

    public function edit(int $id): void
    {
        $template = RecommendationTemplate::findOrFail($id);
        $this->editingId = $id;
        $this->title = $template->title;
        $this->body = $template->body;
        $this->active = $template->active;
    }

    public function save(): void
    {
        $this->validate(['title' => ['required', 'string', 'max:255'], 'body' => ['required', 'string'], 'active' => ['boolean']]);
        RecommendationTemplate::findOrFail($this->editingId)->update(['title' => $this->title, 'body' => $this->body, 'active' => $this->active]);
        $this->reset('editingId', 'title', 'body');
        $this->active = true;
    }

    public function cancel(): void
    {
        $this->reset('editingId', 'title', 'body');
        $this->active = true;
    }

    public function with(): array
    {
        return ['templates' => RecommendationTemplate::query()->when($this->search, fn ($query) => $query->where('title', 'like', '%'.$this->search.'%')->orWhere('code', 'like', '%'.$this->search.'%'))->orderBy('title')->get()];
    }
}; ?>

<?php layout('layouts.app'); ?>
<div class="min-h-screen bg-slate-950 lg:grid lg:grid-cols-[210px_1fr]">
    <x-admin.sidebar active="templates" />
    <main class="min-w-0"><div class="border-b border-white/10 bg-slate-900/70 px-4 py-3 sm:px-6 lg:px-8"><p class="text-sm font-semibold text-slate-300">Recommendation templates</p></div>
    <div class="mx-auto max-w-[1200px] space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        <div><p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">Knowledge base</p><h1 class="mt-2 text-3xl font-bold text-white">Recommendation templates</h1><p class="mt-1 text-sm text-slate-400">Edit the advice shown on future assessment results.</p></div>
        <input wire:model.live="search" type="search" placeholder="Search title or code..." class="w-full max-w-md rounded-lg border-white/10 bg-slate-900 text-sm text-white placeholder-slate-500">
        <div class="grid gap-4 lg:grid-cols-[1.4fr_1fr]">
            <section class="space-y-3">@forelse ($templates as $template)<article class="rounded-xl border border-white/10 bg-slate-900 p-5"><div class="flex items-start justify-between gap-4"><div><p class="text-xs text-slate-500">{{ $template->code }}</p><h2 class="mt-1 font-bold text-white">{{ $template->title }}</h2></div><span class="rounded-full px-2 py-1 text-xs {{ $template->active ? 'bg-teal-400/10 text-teal-300' : 'bg-slate-800 text-slate-500' }}">{{ $template->active ? 'Active' : 'Inactive' }}</span></div><p class="mt-3 text-sm leading-6 text-slate-400">{{ $template->body }}</p><button wire:click="edit({{ $template->id }})" class="mt-4 text-sm font-semibold text-teal-300 hover:text-teal-200">Edit template &rarr;</button></article>@empty<p class="rounded-xl border border-white/10 bg-slate-900 p-5 text-sm text-slate-400">No templates found.</p>@endforelse</section>
            <section class="rounded-xl border border-white/10 bg-slate-900 p-5">@if ($editingId)<h2 class="font-bold text-white">Edit template</h2><form wire:submit="save" class="mt-4 space-y-4"><div><x-input-label for="title" value="Title" class="text-slate-300" /><x-text-input wire:model="title" id="title" class="mt-1 block w-full bg-slate-800 text-white" /><x-input-error :messages="$errors->get('title')" /></div><div><x-input-label for="body" value="Advice" class="text-slate-300" /><textarea wire:model="body" id="body" rows="6" class="mt-1 block w-full rounded-lg border-white/10 bg-slate-800 text-sm text-white"></textarea><x-input-error :messages="$errors->get('body')" /></div><label class="flex items-center gap-2 text-sm text-slate-300"><input wire:model="active" type="checkbox" class="rounded text-teal-500"> Active</label><div class="flex gap-2"><x-primary-button>Save changes</x-primary-button><button type="button" wire:click="cancel" class="rounded-lg border border-white/10 px-4 py-2 text-sm text-slate-300">Cancel</button></div></form>@else<p class="text-sm leading-6 text-slate-400">Select a template to edit its advice and activation status.</p>@endif</section>
        </div>
    </div></main>
</div>
