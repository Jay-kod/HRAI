@props(['level'])

@php
    $styles = match ($level) {
        'low' => 'bg-green-100 text-green-800 ring-green-600/20',
        'moderate' => 'bg-amber-100 text-amber-800 ring-amber-600/20',
        'high', 'known' => 'bg-red-100 text-red-800 ring-red-600/20',
        default => 'bg-gray-100 text-gray-800 ring-gray-600/20',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-semibold ring-1 ring-inset {$styles}"]) }}>
    <span aria-hidden="true">
        {{ match ($level) {
            'low' => '●',
            'moderate' => '▲',
            'high', 'known' => '!',
            default => '?',
        } }}
    </span>
    <span>{{ ucfirst($level) }}</span>
</span>
