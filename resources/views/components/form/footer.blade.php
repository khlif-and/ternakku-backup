@props([
    'backRoute',
    'backLabel' => 'Batal',
    'submitLabel' => 'Simpan',
    'submitLoadingLabel' => 'Menyimpan...',
])

<div class="flex justify-end mt-8 gap-3">
    <x-button.link href="{{ $backRoute }}" color="gray">
        {{ $backLabel }}
    </x-button.link>
    <x-button.primary type="submit" color="green" wire:loading.attr="disabled">
        <span wire:loading.remove>{{ $submitLabel }}</span>
        <span wire:loading>{{ $submitLoadingLabel }}</span>
    </x-button.primary>
</div>
