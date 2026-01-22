@props([
    'type' => 'button',
    'color' => 'green',
    'size' => 'md',
])

@php
    $colors = [
        'green' => 'bg-green-500 hover:bg-green-600 text-white',
        'blue' => 'bg-blue-500 hover:bg-blue-600 text-white',
        'red' => 'bg-red-500 hover:bg-red-600 text-white',
        'gray' => 'bg-gray-100 hover:bg-gray-200 text-gray-700',
    ];
    $sizes = [
        'sm' => 'px-3 py-1 text-xs',
        'md' => 'px-5 py-2 text-sm',
        'lg' => 'px-8 py-3 text-base',
    ];
    $colorClass = $colors[$color] ?? $colors['green'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $colorClass . ' ' . $sizeClass . ' font-semibold rounded-lg transition-all']) }}>
    {{ $slot }}
</button>
