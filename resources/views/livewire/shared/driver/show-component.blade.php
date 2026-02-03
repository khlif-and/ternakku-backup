<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Pengemudi" subtitle="Informasi detail data pengemudi">
        <x-slot:actions>
            <x-button.link href="{{ route('shared.driver.index', $farm->id) }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Info Pengemudi</h3>
                <div class="flex gap-2">
                    <x-button.action href="{{ route('shared.driver.edit', [$farm->id, $driver->id]) }}"
                        color="blue">Edit</x-button.action>
                    <x-button.primary type="button" wire:click="delete"
                        wire:confirm="Apakah Anda yakin ingin menghapus pengemudi ini?" color="red"
                        size="sm">Hapus</x-button.primary>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nama</label>
                    <p class="mt-1 text-gray-900 font-semibold">{{ $user->name ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p class="mt-1 text-gray-900">{{ $user->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">No. HP</label>
                    <p class="mt-1 text-gray-900">{{ $user->phone_number ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                    <p class="mt-1 text-gray-900">{{ $driver->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </x-admin.feature-card>
</div>