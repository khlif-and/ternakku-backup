<div>
    <x-alert.session />

    <div class="max-w-5xl bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-black text-gray-800 uppercase tracking-tighter">Detail Analisis Susu Individu</h3>
            <div class="flex gap-2">
                <div class="text-[10px] font-black px-3 py-1 {{ $milkAnalysisIndividu->at ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} rounded-full uppercase tracking-widest border border-current">
                    Uji AT: {{ $milkAnalysisIndividu->at ? 'POSITIF' : 'NEGATIF' }}
                </div>
                <div class="text-[10px] font-black px-3 py-1 {{ $milkAnalysisIndividu->ab ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} rounded-full uppercase tracking-widest border border-current">
                    Uji AB: {{ $milkAnalysisIndividu->ab ? 'POSITIF' : 'NEGATIF' }}
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-y-8 gap-x-12">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal Analisis</p>
                <p class="text-sm font-bold text-gray-900">{{ date('d F Y', strtotime($milkAnalysisIndividu->milkAnalysisH->transaction_date)) }}</p>
            </div>

            <div class="space-y-1 md:col-span-2 text-blue-600">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Identitas Ternak</p>
                <div class="flex flex-col">
                    <span class="text-sm font-black uppercase">{{ $milkAnalysisIndividu->livestock->full_name }}</span>
                    <span class="text-xs font-medium tracking-tighter">Eartag: {{ $milkAnalysisIndividu->livestock->eartag_code }}</span>
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Berat Jenis (BJ)</p>
                <p class="text-lg font-black text-blue-600 leading-none">{{ number_format($milkAnalysisIndividu->bj, 4, ',', '.') }}</p>
            </div>

            <hr class="md:col-span-4 border-gray-100">

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">MBRT (Jam)</p>
                <p class="text-sm font-bold text-gray-900 uppercase">{{ $milkAnalysisIndividu->mbrt ?? '-' }}</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fat / Lemak</p>
                <p class="text-sm font-black text-gray-700 uppercase">{{ number_format($milkAnalysisIndividu->fat, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Protein</p>
                <p class="text-sm font-black text-gray-700 uppercase">{{ number_format($milkAnalysisIndividu->protein, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">SNF</p>
                <p class="text-sm font-black text-gray-700 uppercase">{{ number_format($milkAnalysisIndividu->snf, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Solid (TS)</p>
                <p class="text-sm font-black text-gray-700 uppercase">{{ number_format($milkAnalysisIndividu->ts, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Added Water</p>
                <p class="text-sm font-bold text-gray-900 uppercase">{{ number_format($milkAnalysisIndividu->a_water, 2, ',', '.') }}%</p>
            </div>

            <div class="space-y-1 md:col-span-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan Tambahan</p>
                <p class="text-sm text-gray-600 leading-relaxed italic">"{{ $milkAnalysisIndividu->notes ?: 'Tidak ada catatan' }}"</p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t flex justify-between items-center">
            <x-button.link href="{{ route('admin.care-livestock.milk-analysis-individu.index', $farm->id) }}" color="gray">
                KEMBALI
            </x-button.link>
            <div class="flex gap-3">
                <x-button.link href="{{ route('admin.care-livestock.milk-analysis-individu.edit', [$farm->id, $milkAnalysisIndividu->id]) }}" color="blue">
                    EDIT DATA
                </x-button.link>
                <button wire:click="delete" wire:confirm="Hapus data analisis ternak ini secara permanen?" class="px-4 py-2 bg-red-600 text-white text-xs font-black rounded-lg hover:bg-red-700 transition-colors uppercase tracking-widest">
                    Hapus Data
                </button>
            </div>
        </div>
    </div>
</div>