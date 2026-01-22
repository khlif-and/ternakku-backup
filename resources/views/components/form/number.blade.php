@props([
    'name' => '',
    'label' => '',
    'placeholder' => '0',
    'required' => false,
    'disabled' => false,
    'step' => '1',
    'min' => null,
    'max' => null,
    'value' => '',
])

<div>
    @if($label)
        <label class="block mb-2 text-base font-semibold text-gray-700">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <input 
        type="number" 
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 border rounded-lg text-base transition-all ' . ($disabled ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-2 focus:ring-blue-500 focus:border-blue-500')]) }}
        placeholder="{{ $placeholder }}"
        step="{{ $step }}"
        @if($min !== null) min="{{ $min }}" @endif
        @if($max !== null) max="{{ $max }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($value !== '') value="{{ $value }}" @endif
    >
    @if($name)
        @error($name) <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    @endif
</div>
