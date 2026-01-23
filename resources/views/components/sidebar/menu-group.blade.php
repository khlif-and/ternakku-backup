@props([
    'name',
    'label',
])

<li>
    <button @click="{{ $name }} = !{{ $name }}" type="button"
        class="w-full flex items-center justify-between px-4 py-2 font-medium hover:bg-white/10 transition">
        <span class="sidebar-label">{{ $label }}</span>
        <svg :class="{ 'rotate-180': {{ $name }} }" class="w-4 h-4" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-cloak x-show="{{ $name }}" x-transition
        class="mt-2 bg-white rounded-md shadow px-3 py-2 text-gray-800 space-y-1">
        {{ $slot }}
    </div>
</li>
