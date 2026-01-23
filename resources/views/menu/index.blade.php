<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ternakku - Dashboard Modern</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 text-slate-700" x-data="{ logoutModal: false }">
    <header class="sticky top-0 z-50">
        @include('layouts.admin.header')
    </header>

    <div class="flex flex-col min-h-screen">
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div class="mb-12 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold text-blue-600 mb-2">Selamat Datang di Ternakku</h1>
                <p class="text-lg text-slate-600">
                    Manajemen peternakan modern di ujung jari Anda.
                </p>
                <div class="mt-4 inline-block bg-white text-blue-700 text-sm font-semibold px-4 py-2 rounded-full border border-slate-200">
                    🐄 Kandang Aktif: <span class="font-bold text-slate-800">{{ \App\Models\Farm::find(session('selected_farm'))?->name ?? 'Belum dipilih' }}</span>
                </div>
            </div>
            
            @include('layouts.admin.menu_cards')

            <hr class="my-12 border-slate-200">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6">Ringkasan Kandang</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                        <x-menu.stat-card label="Total Ternak" value="1,250" />
                        <x-menu.stat-card label="Ternak Sehat" value="98.5%" valueColor="emerald" />
                        <x-menu.stat-card label="Pakan Hampir Habis" value="3" valueColor="amber" suffix="jenis" />
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6">Pintasan Cepat</h2>
                    <div class="p-6 rounded-2xl shadow-lg bg-white/70 backdrop-blur-lg border border-black/5">
                        <div class="flex flex-col space-y-3">
                            <x-menu.shortcut-link href="#" label="Tambah Ternak Baru" iconName="add-circle-outline" />
                            <x-menu.shortcut-link href="#" label="Tambah Penjualan Ternak" iconName="document-text-outline" />
                            <x-menu.shortcut-link href="#" label="Produksi Susu" iconName="calendar-outline" />
                        </div>
                    </div>
                </div>
            </div>

            @include('menu.partials.activity_card', ['recentActivities' => $recentActivities])
        </main>
    </div>

    @include('layouts.admin.logout_modal')
</body>
</html>
