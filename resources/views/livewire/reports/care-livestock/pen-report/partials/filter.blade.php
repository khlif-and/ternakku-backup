<div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Laporan Kandang</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <x-form.select 
                wire:model="pen_id" 
                label="Pilih Kandang" 
                placeholder="-- Pilih Kandang --"
                :options="$pens->mapWithKeys(fn($pen) => [$pen->id => $pen->name . ' (Kapasitas: ' . $pen->capacity . ')'])->toArray()"
            />
        </div>

        <div>
            <x-form.date wire:model="from_date" label="Dari Tanggal" />
        </div>

        <div>
            <x-form.date wire:model="to_date" label="Sampai Tanggal" />
        </div>

        <div class="flex items-end gap-2">
            <button wire:click="generateReport" wire:loading.attr="disabled"
                class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition disabled:opacity-50">
                <span wire:loading.remove wire:target="generateReport">Tampilkan Laporan</span>
                <span wire:loading wire:target="generateReport">Loading...</span>
            </button>

            @if ($showReport)
                <button wire:click="resetReport" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Reset
                </button>
            @endif
        </div>
    </div>
</div>
