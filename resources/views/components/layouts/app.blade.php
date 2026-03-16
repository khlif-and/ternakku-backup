<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $title ?? 'Ternakku' }}</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @livewireStyles
</head>

<body class="bg-gray-100 min-h-screen antialiased font-sans">
    <div class="min-h-screen">
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @stack('scripts')
    @livewireScripts
</body>

</html>
