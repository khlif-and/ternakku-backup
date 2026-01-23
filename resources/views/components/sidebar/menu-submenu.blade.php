@props([
    'name',
    'label',
])

<div>
    <button @click="{{ $name }} = !{{ $name }}"
        class="w-full flex items-center justify-between px-3 py-1 text-sm hover:bg-gray-100 rounded">
        <span>{{ $label }}</span>
        <svg :class="{ 'rotate-180': {{ $name }} }" class="w-4 h-4" fill="none"
            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-cloak x-show="{{ $name }}" class="ml-4 space-y-1" x-transition>
        {{ $slot }}
    </div>
</div>
