@props([
    'href' => '#',
    'type' => 'primary', // primary, secondary, danger
    'icon' => null,
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
    @if($icon)
        <span class="mr-2 -ml-1 flex items-center">
            {{ $icon }}
        </span>
    @endif
    {{ $slot }}
</x-button.base>
