@props([
    'name' => '',
    'label' => '',
    'placeholder' => 'Pilih opsi',
    'required' => false,
    'disabled' => false,
    'options' => [],
    'selected' => '',
])

<div>
    @if($label)
        <label class="block mb-2 text-base font-semibold text-gray-700">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <select 
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 border rounded-lg text-base transition-all ' . ($disabled ? 'bg-gray-100 cursor-not-allowed' : 'focus:ring-2 focus:ring-blue-500 focus:border-blue-500')]) }}
        @if($required) required @endif
        @if($disabled) disabled @endif
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $value)
            <option value="{{ $key }}" @if((string)$selected === (string)$key) selected @endif>{{ $value }}</option>
        @endforeach
    </select>
    @if($name)
        @error($name) <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    @endif
</div>
