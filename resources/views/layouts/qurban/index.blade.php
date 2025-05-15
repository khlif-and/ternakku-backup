<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ternakku - Kurban</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('admin/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ['{{ asset('admin/css/fonts.min.css') }}']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/kaiadmin.min.css') }}">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('admin/css/demo.css') }}">
</head>

<style>
/* Layout wrapper */
.wrapper {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 320px;
    background-color: #255F38;
    transition: width 0.3s ease;
    overflow: hidden;
    flex-shrink: 0;
}

.sidebar.closed {
    width: 120px;
}

/* Logo branding */
.logo-header {
    background-color: #255F38;
    padding: 24px 20px 0 20px;
    display: block;
}

.navbar-brand {
    font: 700 21px 'Nunito', sans-serif;
    color: white;
    text-shadow: 1px 1px 3px rgba(192, 66, 66, 0.2);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.divider-force {
    height: 1px;
    background-color: rgba(255, 255, 255, 0.4);
    margin: 16px -20px 0 -20px;
}

/* Toggle button */
.sidebar-toggle-btn {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.15);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: rgba(255, 255, 255, 0.7);
    transition: all 0.3s ease;
}

/* Sidebar closed behavior */
.sidebar.closed .navbar-brand,
.sidebar.closed .sidebar-toggle-btn,
.sidebar.closed .nav-item .caret,
.sidebar.closed .nav-item i.fa-chevron-down,
.sidebar.closed .nav-item i.fa-angle-down {
    display: none !important;
}

.sidebar.closed .nav-item {
    display: flex;
    justify-content: center;
    padding-left: 0 !important;
    text-align: center !important;
}

.sidebar.closed .nav-item a {
    text-align: center !important;
    display: block;
    width: 100%;
    padding: 0;
    margin: 0 auto;
}

.sidebar.closed .logo-header {
    align-items: center !important;
}

/* Main content wrapper */
.main-content-wrapper {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    width: 100%;
    transition: all 0.3s ease;
}

/* Header */
.header-wrapper {
    width: 100%;
    background-color: white;
    min-height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    transition: all 0.3s ease;
}

/* Panel utama */
/* Panel utama */
.main-panel {
    margin-left: 290px;
    padding: 24px 8px; /* ✅ PAS banget kiri-kanan tanpa lebay */
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.sidebar.closed + .main-content-wrapper .main-panel {
    margin-left: 110px;
}

.page-inner {
    width: 100%;
    max-width: 1530px;   /* ← ini jarak paling proporsional untuk 3 kartu */
    margin: 0 auto;
    padding: 0;
}












.page-inner .row {
    margin-left: 0;
    margin-right: 0;
}

.page-inner .col-12 {
    padding-left: 12px;
    padding-right: 12px;
}


.page-inner .col-12,
.page-inner .col-sm-6,
.page-inner .col-md-6,
.page-inner .col-xl-4 {
    padding-left: 12px !important;
    padding-right: 12px !important;
}

/* Sub-menu */
.sub-item {
    padding-left: 0 !important;
    margin-left: -10px !important;
    text-align: left !important;
    display: block;
    font-size: 12px !important;
    color: black !important;
}

/* Remove list bullets */
.nav-collapse li::before,
.nav-collapse .nav-item::before,
.nav-collapse .sub-item::before,
.nav-collapse > li > a::before {
    content: none !important;
    display: none !important;
}
</style>







<body id="mainBody" class="sidebar-open">

<div class="wrapper">
  @include('layouts.qurban.sidebar')

  <div class="main-content-wrapper">
    <div class="header-wrapper">
      @include('layouts.qurban.header')
    </div>

    <div class="main-panel">
      <div class="page-inner">
        @yield('content')
      </div>
    </div>
  </div>
</div>





    @include('layouts.admin.logout_modal')

    <!--   Core JS Files   -->
    <script src="{{ asset('admin/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('admin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('admin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('admin/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('admin/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('admin/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('admin/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('admin/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('admin/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('admin/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('admin/js/kaiadmin.min.js') }}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="{{ asset('admin/js/setting-demo.js') }}"></script>
    <script src="{{ asset('admin/js/demo.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('script')
</body>

</html>
