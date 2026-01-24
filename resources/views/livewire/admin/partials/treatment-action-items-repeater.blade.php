@props(['treatments'])

<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <label class="text-base font-semibold text-gray-700">Item Tindakan / Jasa</label>
        <x-button.primary type="button" wire:click="addAction" color="blue" size="sm">
            + Tambah Tindakan
        </x-button.primary>
    </div>

    <div class="space-y-4">
        @foreach($treatments as $index => $treatment)
            <div class="p-4 bg-gray-50 rounded-lg border">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-form.input wire:model="treatments.{{ $index }}.name" label="Nama Tindakan" placeholder="Contoh: Injeksi, Jasa Dokter" />
                    <x-form.number wire:model="treatments.{{ $index }}.cost" label="Biaya (Rp)" step="0.01" placeholder="0" />
                    
                    <div class="flex items-end">
                        @if(count($treatments) > 1)
                            <x-button.primary type="button" wire:click="removeAction({{ $index }})" color="red" size="sm">
                                Hapus
                            </x-button.primary>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <x-form.error name="treatments" />
</div>