<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.date wire:model="transaction_date" name="transaction_date" label="Tanggal Penimbangan" />

                <x-form.select wire:model="livestock_id" name="livestock_id" label="Ternak"
                    :options="$livestocks->pluck('eartag', 'id')" placeholder="Pilih Ternak" />

                <x-form.number wire:model="weight" name="weight" label="Berat (Kg)" min="0" step="0.01" />

                <x-form.textarea wire:model="notes" name="notes" label="Catatan" placeholder="Catatan tambahan..." />
            </div>

            <div class="space-y-2">
                <x-form.input type="file" wire:model="photo" name="photo" label="Foto Bukti (Opsional)"
                    accept="image/*" />
                @if ($photo)
                    <div class="mt-2">
                        <p class="text-xs text-gray-500 mb-1">Preview Foto Baru:</p>
                        <img src="{{ $photo->temporaryUrl() }}" class="h-40 rounded-lg border object-cover">
                    </div>
                @elseif($current_photo)
                    <div class="mt-2">
                        <p class="text-xs text-gray-500 mb-1">Foto Saat Ini:</p>
                        <img src="{{ $current_photo }}" class="h-40 rounded-lg border object-cover">
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('shared.reweight.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Perubahan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>