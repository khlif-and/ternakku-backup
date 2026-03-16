<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="text-sm font-medium text-gray-500">Total Penerimaan</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_penerimaan'] }}</div>
        <div class="text-xs text-gray-400 mt-1">Transaksi penerimaan</div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="text-sm font-medium text-gray-500">Total Ternak</div>
        <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['total_ternak'] }}</div>
        <div class="text-xs text-gray-400 mt-1">Ekor hewan qurban</div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="text-sm font-medium text-gray-500">Total Berat</div>
        <div class="mt-2 text-3xl font-bold text-orange-600">{{ number_format($stats['total_berat'], 1) }} Kg</div>
        <div class="text-xs text-gray-400 mt-1">Berat keseluruhan</div>
    </div>
</div>