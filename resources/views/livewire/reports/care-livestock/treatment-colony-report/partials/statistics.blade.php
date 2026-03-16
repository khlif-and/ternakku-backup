<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    {{-- Total Perawatan --}}
    <div
        class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-md p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-indigo-100 text-sm font-medium mb-1">Total Aktivitas Perawatan</p>
                <h3 class="text-3xl font-bold">{{ number_format($stats['total_treatments'] ?? 0, 0, ',', '.') }} <span
                        class="text-lg font-normal">Kali</span></h3>
                <p class="text-indigo-200 text-xs mt-1">periode terpilih</p>
            </div>
            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
</div>