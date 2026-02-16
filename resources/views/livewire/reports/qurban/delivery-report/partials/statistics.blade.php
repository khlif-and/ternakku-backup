<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="text-sm font-medium text-gray-500">Total Pengiriman</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">
            {{ $data->total() ?? 0 }}
        </div>
        <div class="text-xs text-gray-400 mt-1">Periode terpilih</div>
    </div>
</div>