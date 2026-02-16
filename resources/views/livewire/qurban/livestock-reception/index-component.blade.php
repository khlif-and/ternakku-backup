<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Tanggal Mulai">
            <input type="date" wire:model.live="end_date"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Tanggal Akhir">

            <select wire:model.live="livestock_type_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Jenis Ternak</option>
                @foreach($livestockTypes as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>

            <input type="text" wire:model.live.debounce.300ms="supplier"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Cari supplier...">
        </div>
        <x-button.link href="{{ route('qurban.livestock-reception.create') }}" color="green">
            + Tambah Penerimaan
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'No Transaksi', 'class' => 'text-left'],
            ['label' => 'Supplier', 'class' => 'text-left'],
            ['label' => 'Eartag', 'class' => 'text-left'],
            ['label' => 'Jenis', 'class' => 'text-left'],
            ['label' => 'Ras', 'class' => 'text-left'],
            ['label' => 'Kelamin', 'class' => 'text-center'],
            ['label' => 'Berat (Kg)', 'class' => 'text-center'],
            ['label' => 'Harga Qurban', 'class' => 'text-right'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($items as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm font-medium text-gray-500">{{ $items->firstItem() + $loop->index }}
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->livestockReceptionH?->transaction_date ? date('d/m/Y', strtotime($item->livestockReceptionH->transaction_date)) : '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm font-semibold text-gray-700">
                    {{ $item->livestockReceptionH?->transaction_number ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-600">
                    {{ $item->livestockReceptionH?->supplier ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm font-bold text-gray-900">
                    {{ $item->eartag_number }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-600">
                    {{ $item->livestockType?->name ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-600">
                    {{ $item->livestockBreed?->name ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-center text-gray-600">
                    {{ $item->livestockSex?->name ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-center font-bold text-gray-800">
                    {{ number_format($item->weight ?? 0, 1) }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-right text-gray-800">
                    @if($item->livestock?->qurbanLivestock?->price)
                        Rp {{ number_format($item->livestock->qurbanLivestock->price, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('qurban.livestock-reception.show', $item->id) }}"
                            color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('qurban.livestock-reception.edit', $item->id) }}"
                            color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus penerimaan ini?" color="red"
                            size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="11" empty="Tidak ada data penerimaan ternak qurban ditemukan." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>