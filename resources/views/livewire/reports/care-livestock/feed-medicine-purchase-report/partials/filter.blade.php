<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-5">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="w-full">
            <x-form.date label="Tanggal Mulai" wire:model="start_date" />
        </div>
        <div class="w-full">
            <x-form.date label="Tanggal Akhir" wire:model="end_date" />
        </div>
        <div class="w-full">
            <x-form.select label="Tipe Pembelian" wire:model.live="purchase_type" :options="[
        'forage' => 'Pakan Hijauan',
        'concentrate' => 'Pakan Konsentrat',
        'medicine' => 'Obat-obatan'
    ]" placeholder="Semua Tipe" />
        </div>
        <div class="w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
            <input type="text" wire:model.live.debounce.500ms="supplier" placeholder="Cari Supplier..."
                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
        </div>
    </div>
    <div class="mt-4 flex justify-end">
        <button wire:click="generateReport" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Terapkan Filter
        </button>
    </div>
</div>