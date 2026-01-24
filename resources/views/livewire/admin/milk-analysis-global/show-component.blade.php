<div>
    <x-alert.session />

    <div class="max-w-5xl bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-black text-gray-800 uppercase tracking-tighter">Detail Analisis Susu Global</h3>
            <div class="text-[10px] font-black px-3 py-1 {{ $milkAnalysisGlobal->at ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} rounded-full uppercase tracking-widest">
                Uji Alkohol (AT): {{ $milkAnalysisGlobal->at ? 'POSITIF' : 'NEGATIF' }}
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-y-8 gap-x-12">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal Analisis</p>
                <p class="text-sm font-bold text-gray-900">{{ date('d F Y', strtotime($milkAnalysisGlobal->transaction_date)) }}</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Berat Jenis (BJ)</p>
                <p class="text-lg font-black text-blue-600">{{ number_format($milkAnalysisGlobal->bj, 4, ',', '.') }}</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Uji Didih (AB)</p>
                <p class="text-sm font-bold {{ $milkAnalysisGlobal->ab ? 'text-red-600' : 'text-gray-900' }} uppercase">
                    {{ $milkAnalysisGlobal->ab ? 'POSITIF' : 'NEGATIF' }}
                </p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">MBRT (Jam)</p>
                <p class="text-sm font-bold text-gray-900 uppercase">{{ $milkAnalysisGlobal->mbrt ?? '-' }}</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fat / Lemak</p>
                <p class="text-sm font-black text-blue-700 uppercase">{{ number_format($milkAnalysisGlobal->fat, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Protein</p>
                <p class="text-sm font-black text-blue-700 uppercase">{{ number_format($milkAnalysisGlobal->protein, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">SNF</p>
                <p class="text-sm font-black text-blue-700 uppercase">{{ number_format($milkAnalysisGlobal->snf, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Solid (TS)</p>
                <p class="text-sm font-black text-blue-700 uppercase">{{ number_format($milkAnalysisGlobal->ts, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Added Water</p>
                <p class="text-sm font-bold text-gray-900 uppercase">{{ number_format($milkAnalysisGlobal->a_water, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1 md:col-span-3">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan Tambahan</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $milkAnalysisGlobal->notes ?: '-' }}</p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t flex justify-between items-center">
            <x-button.link href="{{ route('admin.care-livestock.milk-analysis-global.index', $farm->id) }}" color="gray">
                KEMBALI
            </x-button.link>
            <div class="flex gap-3">
                <x-button.link href="{{ route('admin.care-livestock.milk-analysis-global.edit', [$farm->id, $milkAnalysisGlobal->id]) }}" color="blue">
                    EDIT DATA
                </x-button.link>
                <button wire:click="delete" wire:confirm="Hapus data analisis permanen?" class="px-4 py-2 bg-red-600 text-white text-xs font-black rounded-lg hover:bg-red-700 transition-colors uppercase tracking-widest">
                    Hapus Data
                </button>
            </div>
        </div>
    </div>
</div>