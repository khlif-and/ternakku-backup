<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Tanggal Awal">
            <input type="date" wire:model.live="end_date"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Tanggal Akhir">
            <input type="text" wire:model.live.debounce.500ms="search"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Cari Eartag...">
        </div>
        <x-button.link href="{{ route('shared.reweight.create', $farm->id) }}" color="green">
            + Tambah Penimbangan
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Ternak', 'class' => 'text-left'],
            ['label' => 'Berat (Kg)', 'class' => 'text-right'],
            ['label' => 'Catatan', 'class' => 'text-left'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($reweights as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $reweights->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ date('d/m/Y', strtotime($item->livestockReweightH->transaction_date)) }}
                </td>
                <td class="px-4 py-3 border-b text-sm font-medium text-gray-900">
                    {{ $item->livestockReweightH->transaction_number }}
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    <div class="font-bold">{{ $item->livestock->eartag ?? '-' }}</div>
                    <div class="text-xs text-gray-500">{{ $item->livestock->livestockType->name ?? '-' }}</div>
                </td>
                <td class="px-4 py-3 border-b text-sm text-right font-mono">
                    {{ $item->weight }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-500 truncate max-w-xs">
                    {{ $item->livestockReweightH->notes ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('shared.reweight.show', [$farm->id, $item->id]) }}"
                            color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('shared.reweight.edit', [$farm->id, $item->id]) }}"
                            color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus data ini?" color="red"
                            size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="7" empty="Tidak ada data penimbangan ditemukan." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $reweights->links() }}
    </div>
</div>