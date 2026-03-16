@if($showReport && !empty($summary))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col items-center justify-center">
            <span class="text-sm font-medium text-gray-500 mb-1">Rata-rata Lemak</span>
            <span class="text-3xl font-bold text-emerald-600">{{ number_format($summary['avg_fat'], 2) }}%</span>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col items-center justify-center">
            <span class="text-sm font-medium text-gray-500 mb-1">Rata-rata SNF</span>
            <span class="text-3xl font-bold text-blue-600">{{ number_format($summary['avg_snf'], 2) }}%</span>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col items-center justify-center">
            <span class="text-sm font-medium text-gray-500 mb-1">Rata-rata Protein</span>
            <span class="text-3xl font-bold text-purple-600">{{ number_format($summary['avg_protein'], 2) }}%</span>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col items-center justify-center">
            <span class="text-sm font-medium text-gray-500 mb-1">Rata-rata BJ</span>
            <span class="text-3xl font-bold text-orange-600">{{ number_format($summary['avg_bj'], 2) }}</span>
        </div>
    </div>
@endif