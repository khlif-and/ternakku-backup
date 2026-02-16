<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="w-full">
            <x-form.date label="Tanggal Mulai" wire:model.defer="start_date" />
        </div>

        <div class="w-full">
            <x-form.date label="Tanggal Akhir" wire:model.defer="end_date" />
        </div>

        <div class="w-full">
            <x-form.select label="Jenis Ternak" wire:model.defer="livestock_type_id" :options="$livestockTypeOptions"
                placeholder="Semua Jenis" />
        </div>

        <div class="w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
            <input type="text" wire:model.defer="supplier" placeholder="Cari supplier..."
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" />
        </div>
    </div>

    <div class="mt-4 flex justify-end">
        <button wire:click="generateReport" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Terapkan Filter
        </button>
    </div>
</div>