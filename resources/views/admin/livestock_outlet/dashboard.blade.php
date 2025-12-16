<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Outlet Ternak</title>
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Menambahkan font default yang lebih menarik */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">

<div class="p-6 sm:p-8">
    {{-- HEADER HALAMAN --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Dashboard Outlet Ternak
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Ringkasan data peternakan dan ternak yang tersedia untuk dijual.
        </p>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Total Peternakan --}}
        <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-6">
            <div class="bg-red-100 p-4 rounded-full">
                <svg class="w-8 h-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18h18a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0119.5 18h-18a2.25 2.25 0 01-2.25-2.25V5.25A2.25 2.25 0 012.25 3z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Peternakan Aktif</p>
                {{-- Data akan diisi oleh JavaScript --}}
                <p id="totalFarms" class="text-3xl font-bold text-gray-800">0</p>
            </div>
        </div>
        {{-- Total Ternak --}}
        <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-6">
            <div class="bg-green-100 p-4 rounded-full">
                 <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0-2.25l2.25 1.313M4.5 15.75l2.25-1.313M6.75 14.437V17.25m-2.25-2.813l2.25 1.313M17.25 14.437l2.25 1.313M19.5 15.75l-2.25-1.313m0 0v2.813m2.25-2.813l-2.25 1.313m-9-1.5l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0-2.25l2.25 1.313M7.5 4.5l2.25-1.313M9.75 3.187V6m-2.25-2.813l2.25 1.313M14.25 3.187l2.25 1.313M16.5 4.5l-2.25-1.313m0 0V6" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Ternak Tersedia</p>
                 {{-- Data akan diisi oleh JavaScript --}}
                <p id="totalLivestocks" class="text-3xl font-bold text-gray-800">0</p>
            </div>
        </div>
    </div>

    {{-- KARTU TERNAK BERDASARKAN TIPE --}}
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Ternak Tersedia Berdasarkan Tipe</h2>
        <div id="livestockCardsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Kartu akan di-generate oleh JavaScript --}}
        </div>
    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- DATA CONTOH (MENGGANTIKAN DATA DARI LARAVEL) ---
        const totalFarmsData = 8;
        const totalLivestocksData = 154;
        const livestockData = [
            { name: 'Sapi Limosin', total: 45, imageUrl: 'https://placehold.co/600x400/FFC1C1/8B0000?text=Sapi+Limosin' },
            { name: 'Kambing Etawa', total: 62, imageUrl: 'https://placehold.co/600x400/C1FFC1/008B00?text=Kambing+Etawa' },
            { name: 'Domba Garut', total: 35, imageUrl: 'https://placehold.co/600x400/E0FFFF/00008B?text=Domba+Garut' },
            { name: 'Sapi Brahman', total: 12, imageUrl: 'https://placehold.co/600x400/FFFACD/8B4513?text=Sapi+Brahman' },
        ];
        // --- AKHIR DATA CONTOH ---


        // Memperbarui kartu statistik
        document.getElementById('totalFarms').innerText = totalFarmsData.toLocaleString('id-ID');
        document.getElementById('totalLivestocks').innerText = totalLivestocksData.toLocaleString('id-ID');


        // Logika untuk membuat dan menampilkan kartu ternak
        const container = document.getElementById('livestockCardsContainer');
        if(container){
            let cardsHTML = '';
            livestockData.forEach(item => {
                cardsHTML += `
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-1.5 transition-transform duration-300 ease-in-out">
                        <img src="${item.imageUrl}" alt="${item.name}" class="w-full h-40 object-cover">
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 truncate">${item.name}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Tersedia: <span class="font-semibold text-gray-800">${item.total.toLocaleString('id-ID')}</span> Ekor
                            </p>
                            <a href="#" class="mt-4 block w-full bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition-colors text-center">
                                Lihat Daftar
                            </a>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = cardsHTML;
        }
    });
</script>

</body>
</html>

