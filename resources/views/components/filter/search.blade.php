@props([
    'placeholder' => 'Cari...',
])

<input type="text" 
    {{ $attributes->merge(['class' => 'px-4 py-2 border rounded-lg text-sm w-full md:w-64']) }}
    placeholder="{{ $placeholder }}">
