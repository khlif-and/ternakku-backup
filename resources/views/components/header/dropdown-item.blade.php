@props([
    'href',
    'label',
    'danger' => false,
])

@if($danger)
    <a href="{{ $href }}"
       class="block px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded">
        {{ $label }}
    </a>
@else
    <a href="{{ $href }}"
       class="block px-3 py-2 text-sm hover:bg-slate-100 rounded">
        {{ $label }}
    </a>
@endif
