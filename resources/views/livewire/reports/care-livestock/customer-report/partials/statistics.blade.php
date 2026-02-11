<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    {{-- Total Pelanggan --}}
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-md p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-indigo-100 text-sm font-medium mb-1">Total Pelanggan</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['total_customers'] ?? 0) }}</h3>
                <p class="text-indigo-200 text-xs mt-1">pelanggan terdaftar</p>
            </div>
            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Pesanan --}}
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Total Pesanan</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['total_orders'] ?? 0) }}</h3>
                <p class="text-purple-200 text-xs mt-1">transaksi berhasil</p>
            </div>
            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
