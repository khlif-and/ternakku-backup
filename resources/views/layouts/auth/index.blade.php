<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Ternakku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <script src="{{ asset('admin/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { "families": ["Public Sans:300,400,500,600,700"] },
            custom: {
                "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['{{ asset('admin/css/fonts.min.css') }}']
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
</head>
<body class="antialiased font-sans">

    @yield('content')

    <!-- Optional JS -->
    <script src="{{ asset('admin/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('admin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin/js/kaiadmin.min.js') }}"></script>
</body>
</html>
