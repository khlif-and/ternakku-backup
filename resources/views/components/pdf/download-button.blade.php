@props([
    'file' => null,
    'mode' => 'action', // 'action' or 'link'
    'color' => 'green',
    'title' => 'Download File'
])

@if($file)
    @php
        $fileUrl = filter_var($file, FILTER_VALIDATE_URL) ? $file : '/storage/' . $file;
    @endphp
    @if($mode === 'link')
        <x-button.link href="{{ $fileUrl }}" target="_blank" color="{{ $color }}" {{ $attributes }}>
            {{ $slot->isEmpty() ? 'Download' : $slot }}
            </x-button.link>
    @else
        <x-button.action href="{{ $fileUrl }}" target="_blank" color="{{ $color }}" title="{{ $title }}" {{ $attributes }}>
            {{ $slot->isEmpty() ? 'Unduh' : $slot }}
        </x-button.action>
    @endif
@endif
