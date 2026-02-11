<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Total Transaksi</div>
        <div class="text-2xl font-bold text-emerald-600">{{ $stats['total_transactions'] ?? 0 }}</div>
    </div>
    
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100">
        <div class="text-sm text-gray-500 mb-1">Total Nominal Pembelian</div>
        <div class="text-2xl font-bold text-emerald-600">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</div>
    </div>
</div>
