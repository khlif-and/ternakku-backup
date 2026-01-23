@props([
    'menuStates' => '{}',
])

<aside
    x-data="{{ $menuStates }}"
    :class="{ 'closed': sidebarCollapsed }"
    class="sidebar text-white relative z-30"
>
    {{ $slot }}
</aside>
