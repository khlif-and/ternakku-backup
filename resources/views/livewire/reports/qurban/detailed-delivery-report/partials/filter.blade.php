<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="w-full">
            <x-form.date label="Tanggal Mulai" wire:model.defer="start_date" />
        </div>

        <div class="w-full">
            <x-form.date label="Tanggal Akhir" wire:model.defer="end_date" />
        </div>

        <div class="w-full">
            <x-form.select label="Driver" wire:model.defer="driver_id" :options="$driverOptions"
                placeholder="Semua Driver" />
        </div>

        <div class="w-full">
            <x-form.select label="Armada" wire:model.defer="fleet_id" :options="$fleetOptions"
                placeholder="Semua Armada" />
        </div>

        <div class="w-full">
            <x-form.select label="Status" wire:model.defer="status" :options="[
        'scheduled' => 'Scheduled',
        'ready_to_deliver' => 'Siap Dikirim',
        'in_delivery' => 'Dalam Pengiriman',
        'delivered' => 'Terkirim',
    ]" placeholder="Semua Status" />
        </div>
    </div>

    <div class="mt-4 flex justify-end">
        <button wire:click="generateReport" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Terapkan Filter
        </button>
    </div>
</div>