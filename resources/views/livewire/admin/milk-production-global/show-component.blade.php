<div>
    <x-alert.session />

    <div class="max-w-4xl bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-black text-gray-800 uppercase tracking-tighter">Detail Produksi: {{ $milkProductionGlobal->transaction_number }}</h3>
            <div class="text-xs font-bold px-3 py-1 bg-blue-100 text-blue-700 rounded-full">
                WAKTU: {{ date('H:i', strtotime($milkProductionGlobal->milking_time)) }}
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal Transaksi</p>
                <p class="text-sm font-bold text-gray-900">{{ date('d F Y', strtotime($milkProductionGlobal->transaction_date)) }}</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Jam Perah</p>
                <p class="text-lg font-black text-blue-600">{{ $milkProductionGlobal->milking_time }}</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Pemerah</p>
                <p class="text-sm font-bold text-gray-900 uppercase">{{ $milkProductionGlobal->milker_name }}</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Jumlah Produksi</p>
                <p class="text-sm font-black text-blue-700 uppercase">{{ number_format($milkProductionGlobal->quantity_liters, 2, ',', '.') }} LITER</p>
            </div>

            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kondisi Susu</p>
                <p class="text-sm font-bold text-gray-900 uppercase">{{ $milkProductionGlobal->milk_condition ?: 'TIDAK DICATAT' }}</p>
            </div>

            <div class="space-y-1 md:col-span-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan Tambahan</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $milkProductionGlobal->notes ?: '-' }}</p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end gap-3">
            <button wire:click="delete" wire:confirm="Hapus data permanen?" class="px-4 py-2 bg-red-600 text-white text-xs font-black rounded-lg hover:bg-red-700 transition-colors uppercase">
                Hapus Data
            </button>
        </div>
    </div>
</div>