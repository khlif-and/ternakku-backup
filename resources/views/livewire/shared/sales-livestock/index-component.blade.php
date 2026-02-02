<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Start Date">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="End Date">
            
            <select wire:model.live="qurban_customer_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Pelanggan</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <x-button.link href="{{ route('admin.care-livestock.sales-livestock.create', $farm->id) }}" color="green">
            + Tambah Penjualan
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'Pelanggan', 'class' => 'text-left'],
            ['label' => 'Detail Ternak', 'class' => 'text-left'],
            ['label' => 'Total Ekor', 'class' => 'text-center'],
            ['label' => 'Catatan', 'class' => 'text-left'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($salesLivestocks as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $salesLivestocks->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm font-semibold text-gray-700">
                    {{ $item->transaction_number }}
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->transaction_date ? date('d/m/Y', strtotime($item->transaction_date)) : '-' }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="text-sm font-bold text-gray-900">{{ $item->qurbanCustomer?->user?->name ?? $item->qurbanCustomer?->phone_number ?? '-' }}</div>
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    @foreach($item->qurbanSaleLivestockD as $detail)
                        <div class="text-xs text-gray-700 mb-1">
                            {{ $detail->livestock->livestockType->name ?? '-' }} - {{ $detail->livestock->eartag ?? '-' }} ({{ $detail->weight }} Kg)
                        </div>
                    @endforeach
                </td>
                <td class="px-4 py-3 border-b text-center text-sm">
                    {{ $item->qurbanSaleLivestockD->count() }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-600">
                    {{ Str::limit($item->notes, 50) ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.care-livestock.sales-livestock.show', [$farm->id, $item->id]) }}" color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('admin.care-livestock.sales-livestock.edit', [$farm->id, $item->id]) }}" color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Apakah Anda yakin ingin menghapus data ini?" color="red" size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="8" empty="Tidak ada data penjualan ditemukan." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $salesLivestocks->links() }}
    </div>
</div>