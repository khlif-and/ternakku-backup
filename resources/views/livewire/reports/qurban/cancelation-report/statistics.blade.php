@if($showReport && !empty($summary))
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col items-center justify-center">
            <span class="text-sm font-medium text-gray-500 mb-1">Total Pembatalan</span>
            <span class="text-3xl font-bold text-red-600">
                {{ number_format($summary['total_transactions'] ?? 0, 0, ',', '.') }}
                Transaksi</span>
        </div>
        {{-- Placeholder for future metrics --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col items-center justify-center">
            <span class="text-sm font-medium text-gray-500 mb-1">Status</span>
            <span class="text-3xl font-bold text-gray-600">Cancelled</span>
        </div>
    </div>
@endif