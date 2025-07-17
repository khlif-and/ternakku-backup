<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ternakku - Care Livestock</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }

        .sidebar {
            width: 20rem;
            min-width: 20rem;
            max-width: 20rem;
            transition: width 0.3s ease;
            overflow-y: auto;
            background-color: #255F38;
            scrollbar-width: none;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar.closed {
            width: 4rem !important;
            min-width: 4rem !important;
            max-width: 4rem !important;
        }

        .sidebar.closed .sidebar-label,
        .sidebar.closed .navbar-brand {
            opacity: 0;
            pointer-events: none;
        }

        .arrow-icon {
            transition: transform 0.3s ease;
        }

        .arrow-icon.rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-data="{ sidebarCollapsed: false, submenuOpen: false, logoutModal: false }">
    <div class="flex h-screen overflow-hidden">
        @include('layouts.care_livestock.sidebar', ['farm' => $farm])

        <div class="flex flex-col flex-1 min-w-0 h-screen overflow-y-auto">
            @include('layouts.care_livestock.header')

            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <div x-show="logoutModal" @click.away="logoutModal = false" x-transition
        class="fixed inset-0 z-50 bg-black/30 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white w-full max-w-md mx-4 rounded-xl shadow-xl overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-900">Logout</h3>
                <button @click="logoutModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-6 py-5 text-gray-800">
                Are you sure you want to logout?
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">
                <button @click="logoutModal = false"
                    class="px-4 py-2 rounded-lg text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition font-medium">
                    Cancel
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-bold shadow transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('script')
</body>

</html>
