<div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Laporan IB</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-form.select 
                wire:model="pen_id" 
                label="Pilih Kandang" 
                placeholder="-- Semua Kandang --"
                :options="$pens->mapWithKeys(fn($pen) => [$pen->id => $pen->name])->toArray()"
            />
        </div>

        <div>
            <x-form.date wire:model="start_date" label="Dari Tanggal" />
        </div>

        <div>
            <x-form.date wire:model="end_date" label="Sampai Tanggal" />
        </div>
    </div>

    <div class="mt-4 flex justify-end gap-2">
        <button wire:click="generateReport" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
            Tampilkan Laporan
        </button>
    </div>
</div>
