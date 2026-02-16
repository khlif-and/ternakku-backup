<div>
    <x-alert.session />

    <x-admin.feature-card title="Detail Kandang" subtitle="Informasi detail kandang">
        <x-slot:actions>
            <x-button.link href="{{ route('admin.care-livestock.pens.index', $farm->id) }}" color="gray">
                Kembali
            </x-button.link>
        </x-slot:actions>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">Info Kandang</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Kandang</label>
                            <div class="mt-1 font-bold text-gray-900">{{ $pen->name }}</div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Luas Area</label>
                                <div class="mt-1 font-bold text-gray-900">{{ $pen->area }} m²</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Kapasitas</label>
                                <div class="mt-1 font-bold text-gray-900">{{ $pen->capacity ?? '-' }} ekor</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Populasi</label>
                                <div class="mt-1 text-2xl font-bold text-blue-600">{{ $pen->population ?? 0 }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Kepadatan</label>
                                @php
                                    $percentage = $pen->capacity > 0 ? ($pen->population / $pen->capacity) * 100 : 0;
                                @endphp
                                <div
                                    class="mt-1 text-2xl font-bold {{ $percentage > 90 ? 'text-red-600' : ($percentage > 70 ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ round($percentage) }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t">
                        <x-button.action href="{{ route('admin.care-livestock.pens.edit', [$farm->id, $pen->id]) }}"
                            color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete"
                            wire:confirm="Yakin ingin menghapus kandang ini?" color="red"
                            size="sm">Hapus</x-button.primary>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-4 mb-4">Daftar Ternak di Kandang</h3>

                    @if($pen->livestocks && $pen->livestocks->count() > 0)
                        @php
                            $livestockHeaders = [
                                ['label' => 'No', 'class' => 'text-left w-16'],
                                ['label' => 'Eartag', 'class' => 'text-left'],
                                ['label' => 'Jenis', 'class' => 'text-left'],
                                ['label' => 'Ras', 'class' => 'text-left'],
                                ['label' => 'Berat (kg)', 'class' => 'text-right'],
                            ];
                        @endphp

                        <x-table.wrapper :headers="$livestockHeaders">
                            @foreach($pen->livestocks as $i => $livestock)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 border-b text-sm">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 border-b text-sm font-medium text-gray-900">
                                        {{ $livestock->eartag_number ?? '-' }}</td>
                                    <td class="px-4 py-3 border-b text-sm">{{ $livestock->livestockType?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 border-b text-sm">{{ $livestock->livestockBreed?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 border-b text-sm text-right font-mono">
                                        {{ number_format($livestock->last_weight ?? 0, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </x-table.wrapper>
                    @else
                        <p class="text-sm text-gray-400 italic">Belum ada ternak di kandang ini.</p>
                    @endif
                </div>

                @if($pen->photo)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Foto Kandang</label>
                        <img src="{{ getNeoObject($pen->photo) }}"
                            class="rounded-lg border shadow-sm max-h-96 w-full object-contain bg-gray-50">
                    </div>
                @endif
            </div>
        </div>
    </x-admin.feature-card>
</div>