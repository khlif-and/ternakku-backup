<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ternakku - Kurban</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Public Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }
    </style>
</head>

<style>
    .sidebar {
        width: 20rem;
        min-width: 20rem;
        max-width: 20rem;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        overflow-x: hidden;
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
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .sidebar.closed .sidebar-label,
    .sidebar.closed .navbar-brand {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
    }

    .sidebar .sidebar-label,
    .sidebar .navbar-brand {
        transition: opacity 0.2s;
    }

    .sidebar.closed .submenu-container {
        display: none !important;
    }

    .sidebar.closed .arrow {
        opacity: 0;
    }
</style>


<body id="mainBody" class="bg-gray-100 min-h-screen">


    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('layouts.qurban.sidebar')

        {{-- Sidebar mobile toggle button (optional, tambahkan sesuai kebutuhan) --}}
        <!--
        <button class="fixed top-4 left-4 z-40 lg:hidden bg-[#255F38] text-white p-2 rounded-full shadow-lg focus:outline-none" aria-label="Open Sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        -->

        <div class="flex flex-col flex-1 min-w-0">
            <header
                class="w-full bg-white min-h-[64px] flex items-center justify-between px-6 shadow z-20 transition-all">
                @include('layouts.qurban.header')
            </header>

            <main class="flex-1 transition-all">
                <div>
                    @yield('content')
                </div>
            </main>


        </div>
    </div>

    @include('layouts.admin.logout_modal')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    @yield('script')

    <script>
        document.querySelectorAll('#closeSidebarBtn, #closeSidebarBtn2').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.toggle('closed');
                sidebar.querySelectorAll('.arrow-icon').forEach(svg => {
                    svg.classList.toggle('rotate-180', sidebar.classList.contains('closed'));
                });
            });
        });

        document.querySelectorAll('.submenu-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var target = document.getElementById(btn.dataset.target);
                var expanded = !target.classList.contains('max-h-0');
                document.querySelectorAll('.submenu-container').forEach(function(sub) {
                    if (sub !== target) {
                        sub.style.maxHeight = '0px';
                        sub.classList.add('max-h-0');
                        sub.previousElementSibling.querySelector('.arrow').classList.remove(
                            'rotate-180');
                    }
                });
                if (expanded) {
                    target.style.maxHeight = '0px';
                    target.classList.add('max-h-0');
                    btn.querySelector('.arrow').classList.remove('rotate-180');
                } else {
                    target.classList.remove('max-h-0');
                    target.style.maxHeight = '0px';
                    setTimeout(function() {
                        target.style.maxHeight = target.scrollHeight + 'px';
                    }, 10);
                    btn.querySelector('.arrow').classList.add('rotate-180');
                }
            });
        });
    </script>


</body>

</html>
