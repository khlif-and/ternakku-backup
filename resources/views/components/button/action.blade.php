@props([
    'href' => '#',
    'color' => 'gray',
])

@php
    $colors = [
        'gray' => 'bg-gray-500 hover:bg-gray-600',
        'blue' => 'bg-blue-500 hover:bg-blue-600',
        'red' => 'bg-red-500 hover:bg-red-600',
    ];
    $colorClass = $colors[$color] ?? $colors['gray'];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $colorClass . ' px-3 py-1 text-white text-xs rounded-lg transition-all']) }}>
    {{ $slot }}
</a>
