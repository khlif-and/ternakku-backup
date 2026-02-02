<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input wire:model="name" name="name" label="Nama Armada" placeholder="Masukkan nama armada" />

                <x-form.input wire:model="police_number" name="police_number" label="Plat Nomor"
                    placeholder="Contoh: B 1234 ABC" />
            </div>

            <x-form.input type="file" wire:model="photo" name="photo" label="Foto Armada (Opsional)" accept="image/*" />
            @if ($photo)
                <div class="mt-2">
                    <img src="{{ $photo->temporaryUrl() }}" class="h-40 rounded-lg border object-cover">
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('shared.fleet.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>