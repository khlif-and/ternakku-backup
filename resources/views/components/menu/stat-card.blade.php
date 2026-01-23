@props([
    'label',
    'value',
    'valueColor' => 'slate-800',
    'suffix' => '',
])

@php
    $colorClass = match($valueColor) {
        'emerald' => 'text-emerald-600',
        'amber' => 'text-amber-600',
        'red' => 'text-red-600',
        'blue' => 'text-blue-600',
        default => 'text-slate-800',
    };
@endphp

<div class="p-6 rounded-2xl shadow-lg bg-white/70 backdrop-blur-lg border border-black/5">
    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
    <p class="text-4xl font-bold {{ $colorClass }} mt-1">
        {{ $value }}
        @if($suffix)
            <span class="text-lg">{{ $suffix }}</span>
        @endif
    </p>
</div>
