@props(['items'])

<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <label class="text-base font-semibold text-gray-700">Item Pakan</label>
        <x-button.primary type="button" wire:click="addItem" color="blue" size="sm">
            + Tambah Item
        </x-button.primary>
    </div>

    <div class="space-y-4">
        @foreach($items as $index => $item)
            <div class="p-4 bg-gray-50 rounded-lg border">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <x-form.select wire:model="items.{{ $index }}.type" label="Jenis" :options="['forage' => 'Hijauan', 'concentrate' => 'Konsentrat', 'feed_material' => 'Bahan Pakan']" />
                    <x-form.input wire:model="items.{{ $index }}.name" label="Nama" placeholder="Nama item" />
                    <x-form.number wire:model="items.{{ $index }}.qty_kg" label="Jumlah (kg)" step="0.01" placeholder="0.00" />
                    <x-form.number wire:model="items.{{ $index }}.price_per_kg" label="Harga/kg" step="0.01" placeholder="0" />
                    <div class="flex items-end">
                        @if(count($items) > 1)
                            <x-button.primary type="button" wire:click="removeItem({{ $index }})" color="red" size="sm">
                                Hapus
                            </x-button.primary>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <x-form.error name="items" />
</div>
