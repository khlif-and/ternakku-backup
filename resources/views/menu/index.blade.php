<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ternakku - Dashboard Modern</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('admin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-700">
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
                        <div class="p-6 rounded-2xl shadow-lg bg-white/70 backdrop-blur-lg border border-black/5">
                            <p class="text-sm font-medium text-slate-500">Total Ternak</p>
                            <p class="text-4xl font-bold text-slate-800 mt-1">1,250</p>
                        </div>
                        <div class="p-6 rounded-2xl shadow-lg bg-white/70 backdrop-blur-lg border border-black/5">
                            <p class="text-sm font-medium text-slate-500">Ternak Sehat</p>
                            <p class="text-4xl font-bold text-emerald-600 mt-1">98.5%</p>
                        </div>
                        <div class="p-6 rounded-2xl shadow-lg bg-white/70 backdrop-blur-lg border border-black/5">
                            <p class="text-sm font-medium text-slate-500">Pakan Hampir Habis</p>
                            <p class="text-4xl font-bold text-amber-600 mt-1">3 <span class="text-lg">jenis</span></p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                     <h2 class="text-2xl font-bold text-slate-800 mb-6">Pintasan Cepat</h2>
                    <div class="p-6 rounded-2xl shadow-lg bg-white/70 backdrop-blur-lg border border-black/5">
                        <div class="flex flex-col space-y-3">
                            <a href="#" class="flex items-center p-3 rounded-lg font-semibold bg-indigo-100 text-indigo-800 hover:bg-indigo-700 hover:text-white transition-all duration-200 transform hover:scale-103">
                                <ion-icon name="add-circle-outline" class="text-2xl mr-3"></ion-icon>
                                Tambah Ternak Baru
                            </a>
                            <a href="#" class="flex items-center p-3 rounded-lg font-semibold bg-indigo-100 text-indigo-800 hover:bg-indigo-700 hover:text-white transition-all duration-200 transform hover:scale-103">
                               <ion-icon name="document-text-outline" class="text-xl mr-3"></ion-icon>
                                Tambah Penjualan Ternak
                            </a>
                            <a href="#" class="flex items-center p-3 rounded-lg font-semibold bg-indigo-100 text-indigo-800 hover:bg-indigo-700 hover:text-white transition-all duration-200 transform hover:scale-103">
                                <ion-icon name="calendar-outline" class="text-xl mr-3"></ion-icon>
                                Produksi Susu
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @include('menu.partials.activity_card', ['recentActivities' => $recentActivities])
        </main>
    </div>

    @include('layouts.admin.logout_modal')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card-container');

            cards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    const rotateX = (y - rect.height / 2) / -15;
                    const rotateY = (x - rect.width / 2) / 15;
                    card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
                });

                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)';
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdown = document.getElementById('profileDropdown');
            const logoutBtn = document.getElementById('logoutBtn');
            const modal = document.getElementById('logoutModal');

            if (dropdownBtn) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                });
            }

            document.addEventListener('click', function(e) {
                if (dropdown && !dropdown.contains(e.target) && e.target !== dropdownBtn) {
                    dropdown.classList.add('hidden');
                }
            });

            if (logoutBtn) {
                logoutBtn.addEventListener('click', function() {
                    if(dropdown) dropdown.classList.add('hidden');
                    if(modal) {
                        modal.style.display = 'flex';
                        setTimeout(() => {
                            modal.classList.remove('opacity-0', 'pointer-events-none');
                            modal.classList.add('opacity-100');
                        }, 10);
                    }
                });
            }
        });

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            if(modal) {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');
                setTimeout(() => {
                    modal.style.display = 'none';
                    modal.classList.add('pointer-events-none');
                }, 300);
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") closeLogoutModal();
        });

        document.addEventListener('click', function(e) {
            const modal = document.getElementById('logoutModal');
            if (modal && modal.style.display === 'flex' && e.target === modal) {
                closeLogoutModal();
            }
        });
    </script>
</body>
</html>
