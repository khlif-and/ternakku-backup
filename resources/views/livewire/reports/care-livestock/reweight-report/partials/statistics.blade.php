<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Total Penimbangan</div>
        <div class="text-2xl font-bold text-emerald-600">{{ $stats['total_reweights'] ?? 0 }}</div>
    </div>
    
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Rata-rata Berat</div>
        <div class="text-2xl font-bold text-emerald-600">{{ number_format($stats['avg_weight'] ?? 0, 2, ',', '.') }} kg</div>
    </div>
</div>
