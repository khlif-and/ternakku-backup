@props([
    'startModel' => '',
    'endModel' => '',
])

<div class="flex flex-wrap gap-3">
    <input type="date" {{ $attributes->whereStartsWith('wire:model') }} class="px-4 py-2 border rounded-lg text-sm">
    {{ $slot }}
</div>
