<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Total Kejadian Lahir</div>
        <div class="text-2xl font-bold text-emerald-600">{{ $stats['total_birth_events'] ?? 0 }}</div>
    </div>
    
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Total Anak</div>
        <div class="text-2xl font-bold text-emerald-600">{{ $stats['total_offspring'] ?? 0 }} ekor</div>
    </div>
    
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Anak Hidup</div>
        <div class="text-2xl font-bold text-emerald-600">{{ $stats['alive_offspring'] ?? 0 }}</div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-red-100">
        <div class="text-sm text-gray-500 mb-1">Anak Mati/Abortus</div>
        <div class="text-2xl font-bold text-red-600">{{ $stats['dead_offspring'] ?? 0 }}</div>
    </div>
</div>
