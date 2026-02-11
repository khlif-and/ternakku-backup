<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Total Pemeriksaan</div>
        <div class="text-2xl font-bold text-emerald-600">{{ $stats['total_checks'] ?? 0 }}</div>
    </div>
    
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Positif Bunting</div>
        <div class="text-2xl font-bold text-emerald-600">{{ $stats['total_pregnant'] ?? 0 }}</div>
    </div>
    
    <div class="bg-white p-4 rounded-xl shadow-sm border border-red-100">
        <div class="text-sm text-gray-500 mb-1">Negatif (Tidak Bunting)</div>
        <div class="text-2xl font-bold text-red-600">{{ $stats['total_not_pregnant'] ?? 0 }}</div>
    </div>
</div>
