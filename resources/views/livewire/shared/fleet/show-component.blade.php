<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Armada" subtitle="Informasi detail data armada">
        <x-slot:actions>
            <x-button.link href="{{ route('shared.fleet.index', $farm->id) }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Info Armada</h3>
                        <div class="flex gap-2">
                            <x-button.action href="{{ route('shared.fleet.edit', [$farm->id, $fleet->id]) }}"
                                color="blue">Edit</x-button.action>
                            <x-button.primary type="button" wire:click="delete"
                                wire:confirm="Apakah Anda yakin ingin menghapus armada ini?" color="red"
                                size="sm">Hapus</x-button.primary>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Armada</label>
                            <p class="mt-1 text-gray-900 font-semibold">{{ $fleet->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Plat Nomor</label>
                            <p class="mt-1 text-gray-900 font-mono text-lg">{{ $fleet->police_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            @if($fleet->latestPosition && \Carbon\Carbon::now()->diffInHours($fleet->latestPosition->created_at) <= 24)
                                <span
                                    class="inline-block mt-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">Aktif</span>
                            @else
                                <span
                                    class="inline-block mt-1 px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-sm font-semibold">Tidak
                                    Aktif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">Foto Armada</h3>

                    @if($fleet->photo)
                        <img src="{{ getNeoObject($fleet->photo) }}"
                            class="rounded-lg border shadow-sm max-h-96 w-full object-contain bg-gray-50">
                    @else
                        <div class="h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                            <p class="text-gray-500">Tidak ada foto</p>
                        </div>
                    @endif
                </div>

                @if($fleet->latestPosition)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">Posisi Terakhir</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Latitude</label>
                                <p class="mt-1 text-gray-900 font-mono">{{ $fleet->latestPosition->latitude }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Longitude</label>
                                <p class="mt-1 text-gray-900 font-mono">{{ $fleet->latestPosition->longitude }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-admin.feature-card>
</div>