@props([
    'href' => '#',
    'color' => 'green',
])

<x-button.base :href="$href" :color="$color" {{ $attributes }}>
    {{ $slot }}
</x-button.base>
