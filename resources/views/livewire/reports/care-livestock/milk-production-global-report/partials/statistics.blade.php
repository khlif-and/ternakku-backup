<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    {{-- Total Produksi --}}
    <div
        class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Total Produksi Susu</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['total_production'] ?? 0, 2, ',', '.') }} <span
                        class="text-lg font-normal">Liter</span></h3>
                <p class="text-blue-200 text-xs mt-1">periode terpilih</p>
            </div>
            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
        </div>
    </div>
</div>