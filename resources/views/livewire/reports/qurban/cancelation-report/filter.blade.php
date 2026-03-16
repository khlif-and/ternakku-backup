<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div>
            <x-form.date label="Tanggal Mulai" wire:model="start_date" />
        </div>
        <div>
            <x-form.date label="Tanggal Akhir" wire:model="end_date" />
        </div>
        <div>
            <x-form.select label="Pilih Pelanggan" wire:model="qurban_customer_id" :options="$customerOptions"
                placeholder="Semua Pelanggan" />
        </div>
        <div>
            <x-button.primary wire:click="generateReport" class="w-full justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Tampilkan Laporan
            </x-button.primary>
        </div>
    </div>
</div>