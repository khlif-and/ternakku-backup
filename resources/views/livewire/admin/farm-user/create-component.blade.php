<div>
    <x-alert.session />

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">1. Cari Pengguna</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input wire:model="searchUser" name="searchUser" label="Email / Nomor Telepon" placeholder="Masukkan email atau nomor telepon..." />
                    <div class="flex items-end">
                        <x-button.primary type="button" wire:click="searchUserByEmailOrPhone" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="searchUserByEmailOrPhone">Cari Pengguna</span>
                            <span wire:loading wire:target="searchUserByEmailOrPhone">Mencari...</span>
                        </x-button.primary>
                    </div>
                </div>
            </div>

            @if($foundUser)
                <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                    <div class="font-semibold text-green-700 mb-3">✓ Pengguna Ditemukan</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $foundUser->name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $foundUser->email }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Telepon</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $foundUser->phone_number ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">2. Pilih Role</h3>
                        <x-form.select wire:model="farm_role" name="farm_role" label="Role" :options="collect($roles)->mapWithKeys(fn($r) => [$r => $r])" placeholder="Pilih Role" required />
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <x-button.link href="{{ route('admin.care-livestock.farm-users.index', $farm->id) }}" color="gray">
                            Batal
                        </x-button.link>
                        <x-button.primary type="submit">
                            Tambah Pengguna
                        </x-button.primary>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>