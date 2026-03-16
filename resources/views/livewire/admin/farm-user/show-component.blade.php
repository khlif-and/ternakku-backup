<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Pengguna" subtitle="Informasi detail pengguna farm">
        <x-slot:actions>
            <x-button.link href="{{ route('admin.care-livestock.farm-users.index', $farm->id) }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">Info Pengguna</h3>

                    <div class="space-y-4">
                        @if($farmUser->user?->profile?->photo)
                            <div class="mb-4">
                                <img src="{{ getNeoObject($farmUser->user->profile->photo) }}" alt="Foto Profil"
                                    class="w-full h-48 object-cover rounded-lg">
                            </div>
                        @else
                            <div class="mb-4 w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $farmUser->user->name ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $farmUser->user->email ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Telepon</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $farmUser->user->phone_number ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Role</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $farmUser->farm_role === 'ADMIN' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $farmUser->farm_role === 'ABK' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $farmUser->farm_role === 'DRIVER' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $farmUser->farm_role === 'MARKETING' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                ">
                                    {{ $farmUser->farm_role }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t">
                        <x-button.action
                            href="{{ route('admin.care-livestock.farm-users.edit', [$farm->id, $farmUser->id]) }}"
                            color="blue">Edit Role</x-button.action>
                        <x-button.primary type="button" wire:click="delete"
                            wire:confirm="Yakin ingin menghapus pengguna ini dari farm?" color="red"
                            size="sm">Hapus</x-button.primary>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">Info Farm</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Farm</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $farmUser->farm->name ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Role di Farm</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $farmUser->farm_role }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin.feature-card>
</div>