<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="text-sm font-medium text-gray-500">Total Pengiriman</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_pengiriman'] }}</div>
        <div class="text-xs text-gray-400 mt-1">Surat perintah kirim</div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="text-sm font-medium text-gray-500">Total Pesanan</div>
        <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['total_pesanan'] }}</div>
        <div class="text-xs text-gray-400 mt-1">Surat jalan</div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="text-sm font-medium text-gray-500">Total Ternak</div>
        <div class="mt-2 text-3xl font-bold text-orange-600">{{ $stats['total_ternak'] }}</div>
        <div class="text-xs text-gray-400 mt-1">Ekor hewan</div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="text-sm font-medium text-gray-500">Terkirim</div>
        <div class="mt-2 text-3xl font-bold text-green-600">{{ $stats['total_terkirim'] }}</div>
        <div class="text-xs text-gray-400 mt-1">Pengiriman selesai</div>
    </div>
</div>