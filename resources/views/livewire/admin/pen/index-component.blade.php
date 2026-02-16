<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="text" wire:model.live.debounce.500ms="search"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Cari nama kandang...">
        </div>
        <x-button.link href="{{ route('admin.care-livestock.pens.create', $farm->id) }}" color="green">
            + Tambah Kandang
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Foto', 'class' => 'text-center'],
            ['label' => 'Nama Kandang', 'class' => 'text-left'],
            ['label' => 'Luas (m²)', 'class' => 'text-right'],
            ['label' => 'Kapasitas', 'class' => 'text-center'],
            ['label' => 'Populasi', 'class' => 'text-center'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($pens as $index => $pen)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $pens->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm text-center">
                    @if ($pen->photo)
                        <img src="{{ getNeoObject($pen->photo) }}" alt="{{ $pen->name }}" class="w-12 h-12 object-cover rounded mx-auto">
                    @else
                        <span class="text-gray-400 italic text-xs">-</span>
                    @endif
                </td>
                <td class="px-4 py-3 border-b text-sm font-medium text-gray-900">{{ $pen->name }}</td>
                <td class="px-4 py-3 border-b text-sm text-right font-mono">{{ $pen->area }}</td>
                <td class="px-4 py-3 border-b text-sm text-center">{{ $pen->capacity ?? '-' }}</td>
                <td class="px-4 py-3 border-b text-sm text-center">{{ $pen->population ?? 0 }}</td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.care-livestock.pens.show', [$farm->id, $pen->id]) }}" color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('admin.care-livestock.pens.edit', [$farm->id, $pen->id]) }}" color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $pen->id }})" wire:confirm="Yakin ingin menghapus kandang ini?" color="red" size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="7" empty="Belum ada data kandang." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $pens->links() }}
    </div>
</div>