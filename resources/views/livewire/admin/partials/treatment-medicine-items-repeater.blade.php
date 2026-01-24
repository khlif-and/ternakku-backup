@props(['medicines'])

<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <label class="text-base font-semibold text-gray-700">Item Obat-obatan</label>
        <x-button.primary type="button" wire:click="addMedicine" color="blue" size="sm">
            + Tambah Obat
        </x-button.primary>
    </div>

    <div class="space-y-4">
        @foreach($medicines as $index => $medicine)
            <div class="p-4 bg-gray-50 rounded-lg border">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <x-form.input wire:model="medicines.{{ $index }}.name" label="Nama Obat" placeholder="Nama obat" />
                    <x-form.input wire:model="medicines.{{ $index }}.unit" label="Satuan" placeholder="Contoh: ml, gr, botol" />
                    <x-form.number wire:model="medicines.{{ $index }}.qty_per_unit" label="Jumlah (Qty)" step="0.01" placeholder="0.00" />
                    <x-form.number wire:model="medicines.{{ $index }}.price_per_unit" label="Harga/Unit" step="0.01" placeholder="0" />
                    
                    <div class="flex items-end">
                        @if(count($medicines) > 1)
                            <x-button.primary type="button" wire:click="removeMedicine({{ $index }})" color="red" size="sm">
                                Hapus
                            </x-button.primary>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <x-form.error name="medicines" />
</div>