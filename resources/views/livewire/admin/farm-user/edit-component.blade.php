<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="bg-gray-50 rounded-lg border p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama</label>
                        <div class="mt-1 font-bold text-gray-900">{{ $userName }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <div class="mt-1 font-bold text-gray-900">{{ $userEmail }}</div>
                    </div>
                </div>
            </div>

            <x-form.select wire:model="farm_role" name="farm_role" label="Role"
                :options="collect($roles)->mapWithKeys(fn($r) => [$r => $r])" placeholder="Pilih Role" required />

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('admin.care-livestock.farm-users.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Perubahan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>