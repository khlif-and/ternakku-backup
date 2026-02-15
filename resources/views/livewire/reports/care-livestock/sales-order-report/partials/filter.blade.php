<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="w-full">
            <x-form.date label="Tanggal Mulai" wire:model="start_date" />
        </div>
        <div class="w-full">
            <x-form.date label="Tanggal Akhir" wire:model="end_date" />
        </div>
        <div class="w-full">
            <x-form.select label="Customer" wire:model.live="qurban_customer_id" :options="$customers"
                placeholder="Semua Customer" />
        </div>
        <div class="w-full">
            <x-form.select label="Tipe Ternak" wire:model.live="livestock_type_id" :options="$livestockTypes"
                placeholder="Semua Tipe" />
        </div>
    </div>
    <div class="mt-4 flex justify-end">
        <button wire:click="generateReport" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Terapkan Filter
        </button>
    </div>
</div>