@props([
    'title' => 'Portal',
    'portalName' => 'Portal',
    'colorScheme' => 'blue',
    'navItems' => [],
    'logoutRoute' => 'logout',
    'defaultInitial' => 'U',
    'defaultName' => 'User',
    'sidebarIcon',
])

@php
    $colorMap = [
        'blue' => [
            'bg' => 'bg-blue-800',
            'border' => 'border-blue-700',
            'active' => 'bg-blue-700 text-white',
            'hover' => 'text-blue-100 hover:bg-blue-700',
            'avatar_bg' => 'bg-blue-100',
            'avatar_text' => 'text-blue-700',
            'icon_text' => 'text-blue-800',
        ],
        'emerald' => [
            'bg' => 'bg-emerald-800',
            'border' => 'border-emerald-700',
            'active' => 'bg-emerald-700 text-white',
            'hover' => 'text-emerald-100 hover:bg-emerald-700',
            'avatar_bg' => 'bg-emerald-100',
            'avatar_text' => 'text-emerald-700',
            'icon_text' => 'text-emerald-800',
        ],
    ];
    $c = $colorMap[$colorScheme] ?? $colorMap['blue'];
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $title) - Ternakku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-100 min-h-screen font-sans" x-data="{ sidebarOpen: true, logoutModal: false }">
    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 {{ $c['bg'] }} text-white flex-shrink-0 hidden md:flex md:flex-col"
            :class="{ 'md:hidden': !sidebarOpen }">
            <div class="p-5 {{ $c['border'] }} border-b">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        {{ $sidebarIcon }}
                    </div>
                    <span class="font-bold text-lg">{{ $portalName }}</span>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                @foreach($navItems as $item)
                    <a href="{{ $item['href'] }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $item['active'] ? $c['active'] : $c['hover'] }}">
                        {!! $item['icon'] !!}
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="p-4 {{ $c['border'] }} border-t">
                <button @click="logoutModal = true"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-lg {{ $c['hover'] }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 md:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 {{ $c['avatar_bg'] }} rounded-full flex items-center justify-center">
                            <span class="{{ $c['avatar_text'] }} font-semibold text-sm">{{ substr(auth()->user()->name ?? $defaultInitial, 0, 1) }}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-700 hidden sm:inline">{{ auth()->user()->name ?? $defaultName }}</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <div x-cloak x-show="logoutModal" @click.self="logoutModal = false" x-transition
        class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white w-full max-w-md mx-4 rounded-xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Logout</h3>
            </div>
            <div class="px-6 py-5 text-gray-700">
                Apakah Anda yakin ingin keluar dari akun?
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">
                <button @click="logoutModal = false"
                    class="px-4 py-2 rounded-lg text-gray-600 bg-white border hover:bg-gray-100 transition">
                    Batal
                </button>
                <form action="{{ route($logoutRoute) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
