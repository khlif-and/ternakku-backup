<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Login - Ternakku')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google & Icon Fonts via WebFont Loader (CDN) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons"
          ],
          urls: [
            "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
          ]
        },
        active() { sessionStorage.fonts = true; }
      });
    </script>

    {{-- tempat CSS tambahan halaman (Select2, dll) --}}
    @stack('styles')
</head>
<body class="antialiased font-sans">

    @yield('content')

    <!-- Core JS (LOAD SEKALI SAJA) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Popper kalau memang perlu --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    {{-- <script src="{{ asset('admin/js/kaiadmin.min.js') }}"></script> --}}

    {{-- tempat JS tambahan halaman (Select2, init, dll) --}}
    @stack('scripts')
</body>
</html>
