<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Marketing Portal') - Ternakku</title>
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

        <aside class="w-64 bg-emerald-800 text-white flex-shrink-0 hidden md:flex md:flex-col"
            :class="{ 'md:hidden': !sidebarOpen }">
            <div class="p-5 border-b border-emerald-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg">Marketing Portal</span>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('marketing.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('marketing.dashboard') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('marketing.customer.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('marketing.customer.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pelanggan
                </a>
                <a href="{{ route('marketing.sales-order.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('marketing.sales-order.*') ? 'bg-emerald-700 text-white' : 'text-emerald-100 hover:bg-emerald-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Sales Order
                </a>
            </nav>

            <div class="p-4 border-t border-emerald-700">
                <button @click="logoutModal = true"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-lg text-emerald-100 hover:bg-emerald-700 transition">
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
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                            <span
                                class="text-emerald-700 font-semibold text-sm">{{ substr(auth()->user()->name ?? 'M', 0, 1) }}</span>
                        </div>
                        <span
                            class="text-sm font-medium text-gray-700 hidden sm:inline">{{ auth()->user()->name ?? 'Marketing' }}</span>
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
                <form action="{{ route('marketing.logout') }}" method="POST">
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