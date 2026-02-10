<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Driver Portal') - Ternakku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-data="{ sidebarOpen: true, logoutModal: false }">
    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-blue-800 text-white flex-shrink-0 hidden md:flex md:flex-col"
            :class="{ 'md:hidden': !sidebarOpen }">
            <div class="p-5 border-b border-blue-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg">Driver Portal</span>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('driver.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('driver.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('driver.delivery.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('driver.delivery.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Instruksi Pengiriman
                </a>
            </nav>

            <div class="p-4 border-t border-blue-700">
                <button @click="logoutModal = true"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-lg text-blue-100 hover:bg-blue-700 transition">
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
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span
                                class="text-blue-700 font-semibold text-sm">{{ substr(auth()->user()->name ?? 'D', 0, 1) }}</span>
                        </div>
                        <span
                            class="text-sm font-medium text-gray-700 hidden sm:inline">{{ auth()->user()->name ?? 'Driver' }}</span>
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
                <form action="{{ route('driver.logout') }}" method="POST">
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