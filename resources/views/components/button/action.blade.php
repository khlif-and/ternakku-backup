@props([
    'href' => '#',
    'type' => 'primary', // primary, secondary, danger
])

@php
    // Map semantic 'type' to base component 'color'
    $colorMap = [
        'primary' => 'blue',
        'secondary' => 'gray',
        'danger' => 'red',
    ];
    $color = $colorMap[$type] ?? 'blue';
@endphp

<x-button.base :href="$href" :color="$color" {{ $attributes }}>
    {{ $slot }}
</x-button.base>
