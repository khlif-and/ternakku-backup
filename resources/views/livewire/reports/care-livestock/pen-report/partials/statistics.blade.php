<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col items-center justify-center">
    <span class="text-sm font-medium text-gray-500 mb-1">Total Kapasitas</span>
    <span class="text-3xl font-bold text-gray-800">{{ number_format($summary['total_capacity'], 0, ',', '.') }}</span>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col items-center justify-center">
    <span class="text-sm font-medium text-gray-500 mb-1">Total Populasi</span>
    <span class="text-3xl font-bold text-blue-600">{{ number_format($summary['total_population'], 0, ',', '.') }}</span>
    <span class="text-xs text-gray-400 mt-1">Okupansi: {{ $summary['occupancy_rate'] }}%</span>
</div>