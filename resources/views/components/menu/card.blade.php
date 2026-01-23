@props([
    'href' => '#',
    'label',
    'icon',
    'iconAlt' => '',
    'color' => 'emerald',
    'tag' => 'a',
])

@php
    $baseClasses = 'card-container group relative block w-full text-left transition-all duration-300 ease-out hover:z-10';
    $iconBgClasses = match($color) {
        'blue' => 'bg-blue-100 shadow-blue-500/10 group-hover:bg-blue-200 dark:bg-blue-900/50 dark:group-hover:bg-blue-900',
        'orange' => 'bg-orange-100 shadow-orange-500/10 group-hover:bg-orange-200 dark:bg-orange-900/50 dark:group-hover:bg-orange-900',
        'purple' => 'bg-purple-100 shadow-purple-500/10 group-hover:bg-purple-200 dark:bg-purple-900/50 dark:group-hover:bg-purple-900',
        default => 'bg-emerald-100 shadow-emerald-500/10 group-hover:bg-emerald-200 dark:bg-emerald-900/50 dark:group-hover:bg-emerald-900',
    };
    $textHoverClasses = match($color) {
        'blue' => 'group-hover:text-blue-700 dark:group-hover:text-blue-700',
        'orange' => 'group-hover:text-orange-700 dark:group-hover:text-orange-700',
        'purple' => 'group-hover:text-purple-700 dark:group-hover:text-purple-700',
        default => 'group-hover:text-emerald-700 dark:group-hover:text-emerald-700',
    };
@endphp

@if($tag === 'button')
    <button {{ $attributes->merge(['class' => $baseClasses]) }}>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
@endif
    <div class="card-content relative z-10 flex h-full flex-col items-center justify-center rounded-2xl bg-white/80 p-6 text-center backdrop-blur-sm transition-colors duration-300 dark:bg-white/80">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl {{ $iconBgClasses }} shadow-lg transition-all duration-300 group-hover:scale-110">
            <img src="{{ $icon }}" alt="{{ $iconAlt ?: $label }}" class="h-9 w-9 object-contain transition-transform duration-300 group-hover:scale-95" />
        </div>
        <p class="font-bold text-slate-700 transition-colors duration-300 {{ $textHoverClasses }} dark:text-slate-700">
            {{ $label }}
        </p>
    </div>
@if($tag === 'button')
    </button>
@else
    </a>
@endif
