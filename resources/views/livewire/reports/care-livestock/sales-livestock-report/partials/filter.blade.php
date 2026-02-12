<div class="flex flex-col gap-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Filter Tanggal -->
        <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Tanggal Mulai</label>
            <input type="date" wire:model="start_date" class="input input-bordered w-full" />
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Tanggal Selesai</label>
            <input type="date" wire:model="end_date" class="input input-bordered w-full" />
        </div>

        <!-- Filter Customer -->
        <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Pelanggan</label>
            <select wire:model="qurban_customer_id" class="select select-bordered w-full">
                <option value="">Semua Pelanggan</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex justify-end">
        <button wire:click="filter" class="btn btn-primary text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-5.31 5.31a2 2 0 0 0 -.586 1.414v5.026a1 1 0 0 1 -1.433 .781l-4.144 -4.135a1 1 0 0 1 -.621 -.886v-6.046a2 2 0 0 0 -.586 -1.414l-5.31 -5.31a2 2 0 0 1 -.586 -1.414z" />
            </svg>
            Terapkan Filter
        </button>
    </div>
</div>