<div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Laporan PKB</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <x-form.select 
                wire:model="pen_id" 
                label="Pilih Kandang" 
                placeholder="-- Semua Kandang --"
                :options="$pens->mapWithKeys(fn($pen) => [$pen->id => $pen->name])->toArray()"
            />
        </div>

        <div>
             <x-form.select 
                wire:model="livestock_id" 
                label="Pilih Ternak Betina" 
                placeholder="-- Semua Ternak --"
                :options="$livestocks->mapWithKeys(fn($l) => [$l->id => ($l->eartag_number ?? $l->code) . ' - ' . ($l->livestockType->name ?? '')])->toArray()"
            />
        </div>
        
        <div>
            <x-form.select 
                wire:model="status" 
                label="Status Kebuntingan" 
                placeholder="-- Semua Status --"
                :options="[
                    'PREGNANT' => 'Bunting',
                    'NOT_PREGNANT' => 'Tidak Bunting',
                ]"
            />
        </div>

        <div class="md:col-span-1 grid grid-cols-2 gap-2">
            <x-form.date wire:model="start_date" label="Dari Tanggal" />
            <x-form.date wire:model="end_date" label="Sampai Tanggal" />
        </div>
    </div>

    <div class="mt-4 flex justify-end gap-2">
        <button wire:click="generateReport" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
            Tampilkan Laporan
        </button>
    </div>
</div>
