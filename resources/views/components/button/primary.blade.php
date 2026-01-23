@props([
    'type' => 'button',
    'color' => 'green',
    'size' => 'md',
])

<x-button.base :type="$type" :color="$color" :size="$size" {{ $attributes }}>
    {{ $slot }}
</x-button.base>
