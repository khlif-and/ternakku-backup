<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ternakku - Qurban</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @livewireStyles
</head>

<body class="bg-gray-100 min-h-screen" x-data="{ sidebarCollapsed: false, submenuOpen: false, logoutModal: false }">
    <div class="flex h-screen overflow-hidden">

        @php
            $farm = $farm ?? request()->attributes->get('farm');
            if (!$farm && session()->has('selected_farm')) {
                $farm = \App\Models\Farm::find(session('selected_farm'));
            }
        @endphp

        @include('layouts.qurban.sidebar', ['farm' => $farm])

        <div class="flex flex-col flex-1 min-w-0 h-screen overflow-y-auto">
            @include('layouts.qurban.header')

            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <x-modal.confirm
        title="Konfirmasi Logout"
        message="Apakah Anda yakin ingin keluar dari akun?"
        confirmText="Logout"
        cancelText="Batal"
        :confirmAction="route('logout')"
        icon="logout"
        :danger="true"
    />

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @yield('script')
    @stack('scripts')
    @livewireScripts
</body>

</html>
