@props([
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'rows' => 3,
    'value' => '',
])

<div>
    @if($label)
        <label class="block mb-2 text-base font-semibold text-gray-700">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <textarea 
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 border rounded-lg text-base transition-all resize-none ' . ($disabled ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-2 focus:ring-blue-500 focus:border-blue-500')]) }}
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
    >{{ $value }}</textarea>
    @if($name)
        @error($name) <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    @endif
</div>
