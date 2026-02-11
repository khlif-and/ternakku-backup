<div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
    <div class="flex justify-between items-start mb-4">
        <h3 class="text-lg font-bold text-gray-800">Informasi Kandang: {{ $pen->name }}</h3>
        <button wire:click="exportPdf" wire:loading.attr="disabled"
            class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition flex items-center gap-2 disabled:opacity-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span wire:loading.remove wire:target="exportPdf">Download PDF</span>
            <span wire:loading wire:target="exportPdf">Generating...</span>
        </button>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div>
            <span class="text-gray-500">Kapasitas:</span>
            <span class="font-semibold">{{ $pen->capacity ?? '-' }} ekor</span>
        </div>
        <div>
            <span class="text-gray-500">Luas Area:</span>
            <span class="font-semibold">{{ $pen->area ?? '-' }} m²</span>
        </div>
        <div>
            <span class="text-gray-500">Periode:</span>
            <span class="font-semibold">{{ \Carbon\Carbon::parse($from_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($to_date)->format('d M Y') }}</span>
        </div>
        <div>
            <span class="text-gray-500">Farm:</span>
            <span class="font-semibold">{{ $farm->name }}</span>
        </div>
    </div>
</div>
