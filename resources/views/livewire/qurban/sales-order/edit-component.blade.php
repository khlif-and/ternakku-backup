<div>
    <x-alert.session />
    
    <form wire:submit.prevent="update" class="space-y-6">
        
        <x-form.select 
            wire:model="customer_id" 
            name="customer_id"
            label="Customer" 
            :options="$customers" 
            placeholder="Pilih Customer"
        />

        <x-form.date 
            wire:model="order_date" 
            name="order_date"
            label="Tanggal Order" 
        />

        <div class="space-y-4">
            <div class="flex items-center justify-between border-b pb-2">
                <label class="block text-sm font-medium text-gray-700">Detail Pesanan</label>
                <x-button.action wire:click="addItem" type="button" size="xs" color="blue">
                    + Tambah Hewan
                </x-button.action>
            </div>

            @foreach($items as $index => $item)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-gray-50 p-4 rounded-lg border relative group" wire:key="item-{{ $index }}">
                    <div class="md:col-span-5">
                        <x-form.select 
                            wire:model="items.{{ $index }}.livestock_type_id" 
                            name="items.{{ $index }}.livestock_type_id"
                            label="Jenis Hewan" 
                            :options="$livestockTypes->pluck('name', 'id')" 
                            placeholder="Pilih Jenis Hewan"
                        />
                    </div>
                    <div class="md:col-span-3">
                        <x-form.number 
                            wire:model="items.{{ $index }}.quantity" 
                            name="items.{{ $index }}.quantity"
                            label="Jumlah (Ekor)" 
                            min="1"
                        />
                    </div>
                    <div class="md:col-span-3">
                        <x-form.number 
                            wire:model="items.{{ $index }}.total_weight" 
                            name="items.{{ $index }}.total_weight"
                            label="Total Berat (Kg)" 
                            min="0"
                            step="0.01"
                        />
                    </div>
                    <div class="md:col-span-1 flex justify-center pb-2">
                        @if(count($items) > 1)
                            <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <x-button.link href="{{ route('admin.care-livestock.sales-order.index', $farm->id) }}" color="gray">
                Batal
            </x-button.link>
            <x-button.primary type="submit">
                Simpan Perubahan
            </x-button.primary>
        </div>
    </form>
</div>
