@props([
    'name' => '',
    'label' => '',
    'required' => false,
    'accept' => 'image/*',
    'preview' => null,
])

<div>
    @if($label)
        <label class="block mb-2 text-base font-semibold text-gray-700">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    
    <div class="flex items-center gap-4">
        @if($preview)
            <div class="w-20 h-20 rounded-lg overflow-hidden border bg-gray-50">
                <img src="{{ $preview }}" alt="Preview" class="w-full h-full object-cover">
            </div>
        @endif
        
        <div class="flex-1">
            <input 
                type="file" 
                {{ $attributes->merge(['class' => 'w-full px-4 py-3 border rounded-lg text-base file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all']) }}
                accept="{{ $accept }}"
                @if($required) required @endif
            >
        </div>
    </div>
    
    @if($name)
        @error($name) <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    @endif
</div>
