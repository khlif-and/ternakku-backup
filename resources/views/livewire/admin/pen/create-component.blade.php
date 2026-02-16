<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input wire:model="name" name="name" label="Nama Kandang" required />

                <x-form.number wire:model="capacity" name="capacity" label="Kapasitas (ekor)" min="1" required />
            </div>

            <x-form.number wire:model="area" name="area" label="Luas Area (m²)" step="0.01" required />

            <x-form.file-upload wire:model="photo" name="photo" label="Foto Kandang (Opsional)" accept="image/*" />
            @if ($photo)
                <div class="mt-2">
                    <p class="text-xs text-gray-500 mb-1">Preview Foto:</p>
                    <img src="{{ $photo->temporaryUrl() }}" class="h-40 rounded-lg border object-cover">
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('admin.care-livestock.pens.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>