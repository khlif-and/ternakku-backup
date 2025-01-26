<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TernakKu home</title>

    <!-- Tambahkan link Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

    <!-- Tambahkan style css -->
    <link rel="stylesheet" href="{{ asset('home/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('home/css/banner_2.css') }}">
    <link rel="stylesheet" href="{{ asset('home/css/footer.css') }}">

    <style>
        /* Gunakan font Poppins di seluruh halaman */
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>

    @yield('content')
    <!-- resources/views/home/home.blade.php -->
    @include('home.partials.navbar')
    @include('home.partials.banner')
    @include('home.partials.tentang', ['tentangKami' => $tentangKami])
    @include('home.partials.banner_2')
    @include('home.partials.banner_3')
    @include('home.partials.fitur', ['fiturTernakKu' => $fiturTernakKu])
    @include('home.partials.banner_4')
    @include('home.partials.footer')



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('home/js/nav.js') }}"></script>


</body>

</html>
