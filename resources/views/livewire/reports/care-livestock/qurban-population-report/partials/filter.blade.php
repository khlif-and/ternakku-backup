<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="w-full">
            <x-form.select label="Tipe Ternak" wire:model.live="livestock_type_id" :options="$livestockTypes"
                placeholder="Semua Tipe" />
        </div>
        <div class="w-full">
            <x-form.select label="Ras Ternak" wire:model.live="livestock_breed_id" :options="$livestockBreeds"
                placeholder="Semua Ras" />
        </div>
        <div class="w-full">
            <x-form.select label="Status" wire:model.live="livestock_status_id" :options="$livestockStatuses"
                placeholder="Semua Status" />
        </div>
        <div class="w-full">
            <button wire:click="generateReport"
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Terapkan Filter
            </button>
        </div>
    </div>
</div>