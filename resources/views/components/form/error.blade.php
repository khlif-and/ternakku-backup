@props([
    'name' => '',
])

@error($name)
    <span {{ $attributes->merge(['class' => 'text-red-500 text-xs mt-1 block']) }}>{{ $message }}</span>
@enderror
