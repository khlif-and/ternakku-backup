<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="text" wire:model.live.debounce.500ms="search"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500"
                placeholder="Cari nama atau plat nomor...">
        </div>
        <x-button.link href="{{ route('shared.fleet.create', $farm->id) }}" color="green">
            + Tambah Armada
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Foto', 'class' => 'text-left w-24'],
            ['label' => 'Nama Armada', 'class' => 'text-left'],
            ['label' => 'Plat Nomor', 'class' => 'text-left'],
            ['label' => 'Status', 'class' => 'text-center'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($fleets as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $fleets->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm">
                    @if($item->photo)
                        <img src="{{ getNeoObject($item->photo) }}" class="w-16 h-12 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7l7 5-7 5z" />
                            </svg>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 border-b text-sm font-medium text-gray-900">
                    {{ $item->name }}
                </td>
                <td class="px-4 py-3 border-b text-sm font-mono">
                    {{ $item->police_number }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-center">
                    @if($item->latestPosition && \Carbon\Carbon::now()->diffInHours($item->latestPosition->created_at) <= 24)
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">Tidak Aktif</span>
                    @endif
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('shared.fleet.show', [$farm->id, $item->id]) }}"
                            color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('shared.fleet.edit', [$farm->id, $item->id]) }}"
                            color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus armada ini?" color="red"
                            size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="6" empty="Tidak ada data armada ditemukan." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $fleets->links() }}
    </div>
</div>