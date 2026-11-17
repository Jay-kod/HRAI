<?php

use App\Models\UsabilityFeedback;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use function Livewire\Volt\layout;

new class extends Component
{
    public ?int $ease_of_use = null;
    public ?int $usefulness = null;
    public ?int $confidence = null;
    public ?int $intention = null;
    public ?string $comment = null;

    public function submitFeedback(): void
    {
        $validated = $this->validate([
            'ease_of_use' => ['required', 'integer', 'between:1,5'],
            'usefulness' => ['required', 'integer', 'between:1,5'],
            'confidence' => ['required', 'integer', 'between:1,5'],
            'intention' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);
        UsabilityFeedback::create($validated + ['user_id' => Auth::id(), 'submitted_at' => now()]);
        $this->reset('ease_of_use', 'usefulness', 'confidence', 'intention', 'comment');
        $this->dispatch('feedback-submitted');
    }

    public function with(): array
    {
        return ['feedbackCount' => Auth::user()->isAdmin() ? UsabilityFeedback::count() : null];
    }
}; ?>

<?php layout('layouts.app'); ?>

<div class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6">
    <div><h2 class="text-2xl font-semibold text-gray-900">Usability feedback</h2><p class="mt-1 text-base text-gray-600">Help evaluate whether the prototype is clear and useful.</p></div>
    @if (Auth::user()->isAdmin())<div class="rounded-lg bg-indigo-50 p-4 text-base text-indigo-900">Feedback responses recorded: {{ $feedbackCount }}</div>@endif
    <form wire:submit="submitFeedback" class="space-y-5 rounded-lg bg-white p-6 shadow-sm">
        @foreach (['ease_of_use' => 'Ease of use', 'usefulness' => 'Usefulness', 'confidence' => 'Confidence in the result', 'intention' => 'Intention to use recommendations'] as $field => $label)
            <div><x-input-label :for="$field" :value="$label.' (1–5)'" /><x-text-input wire:model="{{ $field }}" :id="$field" type="number" min="1" max="5" class="mt-1 block w-full" /><x-input-error :messages="$errors->get($field)" class="mt-2" /></div>
        @endforeach
        <div><x-input-label for="comment" value="Comment (optional)" /><textarea wire:model="comment" id="comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea><x-input-error :messages="$errors->get('comment')" class="mt-2" /></div>
        <x-primary-button>Submit feedback</x-primary-button><x-action-message on="feedback-submitted">Thank you.</x-action-message>
    </form>
</div>
