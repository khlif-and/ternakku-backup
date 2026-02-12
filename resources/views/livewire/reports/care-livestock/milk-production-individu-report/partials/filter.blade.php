<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
        {{-- Pen Selection --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Kandang</label>
            <select wire:model.live="pen_id"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                <option value="">Semua Kandang</option>
                @foreach($pens as $pen)
                    <option value="{{ $pen->id }}">{{ $pen->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Livestock Selection --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Ternak</label>
            <select wire:model="livestock_id"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                @if(!$pen_id) disabled @endif>
                <option value="">Semua Ternak</option>
                @foreach($livestocks as $livestock)
                    <option value="{{ $livestock->id }}">{{ $livestock->eartag }} - {{ $livestock->name }}</option>
                @endforeach
            </select>
            @if(!$pen_id)
                <p class="text-xs text-gray-500 mt-1">Pilih kandang terlebih dahulu</p>
            @endif
        </div>

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