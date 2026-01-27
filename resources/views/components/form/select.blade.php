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
    
    <div class="relative">
        <select 
            {{ $attributes->merge(['class' => 'appearance-none w-full px-4 py-3 border rounded-lg text-base transition-all pr-10 ' . ($disabled ? 'bg-gray-100 cursor-not-allowed text-gray-500' : 'bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400')]) }}
            @if($required) required @endif
            @if($disabled) disabled @endif
        >
            <option value="">{{ $placeholder }}</option>
            @foreach($options as $key => $value)
                <option value="{{ $key }}" @if((string)$selected === (string)$key) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
        
        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>

    @if($name)
        @error($name) <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    @endif
</div>
