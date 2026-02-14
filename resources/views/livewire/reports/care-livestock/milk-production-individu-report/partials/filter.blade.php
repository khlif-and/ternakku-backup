<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        {{-- Start Date --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
            <input type="date" wire:model="start_date"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm transition duration-150 ease-in-out">
        </div>

        {{-- End Date --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
            <input type="date" wire:model="end_date"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm transition duration-150 ease-in-out">
        </div>

        {{-- Livestock Filter --}}
        <div>
            <x-form.select label="Pilih Ternak" name="livestock_id" wire:model="livestock_id"
                :options="$livestockOptions" placeholder="Semua Ternak" />
        </div>

        {{-- Filter Button --}}
        <div>
            <button wire:click="generateReport"
                class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-sm transition-all duration-200">
                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Terapkan Filter
            </button>
        </div>
    </div>
</div>