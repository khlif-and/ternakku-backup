@props([
    'href',
    'label',
])

<a href="{{ $href }}"
    class="block hover:bg-gray-100 px-3 py-1 rounded text-sm">
    {{ $label }}
</a>
