@props([
    'href' => '#',
    'color' => 'green',
])

@php
    $colors = [
        'green' => 'bg-green-500 hover:bg-green-600',
        'blue' => 'bg-blue-500 hover:bg-blue-600',
        'red' => 'bg-red-500 hover:bg-red-600',
        'gray' => 'bg-gray-500 hover:bg-gray-600',
        'yellow' => 'bg-yellow-500 hover:bg-yellow-600',
    ];
    $colorClass = $colors[$color] ?? $colors['green'];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $colorClass . ' text-white font-semibold px-5 py-2 rounded-lg transition-all']) }}>
    {{ $slot }}
</a>
