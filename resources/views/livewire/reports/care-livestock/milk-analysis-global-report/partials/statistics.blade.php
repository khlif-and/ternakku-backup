<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm font-medium text-gray-500">Rata-rata Lemak (Fat)</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['avg_fat'] ?? 0, 2) }} %</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm font-medium text-gray-500">Rata-rata SNF</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['avg_snf'] ?? 0, 2) }} %</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm font-medium text-gray-500">Rata-rata Protein</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['avg_protein'] ?? 0, 2) }} %</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm font-medium text-gray-500">Rata-rata BJ</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($summary['avg_bj'] ?? 0, 4) }}</p>
    </div>
</div>