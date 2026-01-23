@props([
    'href' => null,
    'type' => 'button',
    'color' => 'blue',
    'size' => 'md',
    'class' => '',
])

@php
    $colors = [
        'blue' => 'bg-blue-500 hover:bg-blue-600 text-white',
        'gray' => 'bg-gray-400 hover:bg-gray-500 text-white',
        'red' => 'bg-red-500 hover:bg-red-600 text-white',
        'green' => 'bg-green-500 hover:bg-green-600 text-white',
        'yellow' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
        'white' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300',
    ];

    $sizes = [
        'xs' => 'px-3 py-1 text-xs',
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $baseClass = 'inline-flex items-center justify-center font-semibold rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed';
    $colorClass = $colors[$color] ?? $colors['blue'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $finalClass = implode(' ', [$baseClass, $colorClass, $sizeClass, $class]);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $finalClass]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $finalClass]) }}>
        {{ $slot }}
    </button>
@endif
