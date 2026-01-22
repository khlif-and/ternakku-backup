@props([
    'label' => '',
    'value' => '',
])

<div>
    @if($label)
        <label class="block mb-2 text-base font-semibold text-gray-700">{{ $label }}</label>
    @endif
    <input 
        type="text" 
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 border rounded-lg text-base bg-gray-100 cursor-not-allowed']) }}
        value="{{ $value }}"
        disabled
        readonly
    >
</div>
