<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input wire:model="name" name="name" label="Nama" placeholder="Masukkan nama pengemudi" />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" value="{{ $email }}" disabled
                        class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input wire:model="phone_number" name="phone_number" label="No. HP"
                    placeholder="Masukkan nomor HP" />

                <x-form.input wire:model="password" name="password" type="password" label="Password Baru"
                    placeholder="Kosongkan jika tidak ingin mengubah" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <x-button.link href="{{ route('shared.driver.index', $farm->id) }}" color="gray">
                    Batal
                </x-button.link>
                <x-button.primary type="submit">
                    Simpan Perubahan
                </x-button.primary>
            </div>
        </form>
    </div>
</div>