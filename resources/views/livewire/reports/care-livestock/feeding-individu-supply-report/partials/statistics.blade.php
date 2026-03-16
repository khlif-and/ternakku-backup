<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    {{-- Total Penggunaan Pakan --}}
    <div
        class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-md p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-amber-100 text-sm font-medium mb-1">Total Penggunaan Pakan</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['total_kg'] ?? 0, 2) }} <span
                        class="text-lg font-normal">Kg</span></h3>
                <p class="text-amber-200 text-xs mt-1">akumulasi periode ini</p>
            </div>
            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Biaya Pakan --}}
    <div
        class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-md p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-emerald-100 text-sm font-medium mb-1">Total Biaya Pakan</p>
                <h3 class="text-3xl font-bold">Rp {{ number_format($stats['total_cost'] ?? 0, 0, ',', '.') }}</h3>
                <p class="text-emerald-200 text-xs mt-1">estimasi biaya dikeluarkan</p>
            </div>
            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
</div>