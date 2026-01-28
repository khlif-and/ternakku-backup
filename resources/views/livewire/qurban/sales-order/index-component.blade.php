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
        <x-button.link href="{{ route('admin.care-livestock.sales-order.create', $farm->id) }}" color="green">
            + Tambah Sales Order
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Tanggal Order', 'class' => 'text-left'],
            ['label' => 'Pelanggan', 'class' => 'text-left'],
            ['label' => 'Detail Hewan', 'class' => 'text-left'],
            ['label' => 'Total Ekor', 'class' => 'text-center'],
            ['label' => 'Total Berat', 'class' => 'text-center'],
            ['label' => 'Deskripsi', 'class' => 'text-left'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($salesOrders as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $salesOrders->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm font-semibold text-gray-700">
                    {{ $item->transaction_number }}
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->order_date ? date('d/m/Y', strtotime($item->order_date)) : '-' }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="text-sm font-bold text-gray-900">{{ $item->qurbanCustomer?->user?->name ?? $item->qurbanCustomer?->phone_number ?? 'Customer #' . $item->qurbanCustomer?->id ?? '-' }}</div>
                    <div class="text-xs text-gray-500">{{ $item->qurbanCustomer?->phone ?? '' }}</div>
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    @foreach($item->qurbanSalesOrderD as $detail)
                        <div class="text-xs text-gray-700">
                            {{ $detail->livestockType->name ?? '-' }} ({{ $detail->quantity }} Ekor, {{ $detail->total_weight }} Kg)
                        </div>
                    @endforeach
                </td>
                <td class="px-4 py-3 border-b text-center text-sm">
                    {{ $item->qurbanSalesOrderD->sum('quantity') }}
                </td>
                <td class="px-4 py-3 border-b text-center text-sm">
                    {{ $item->qurbanSalesOrderD->sum('total_weight') }} Kg
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-600">
                    {{ Str::limit($item->description, 50) ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.care-livestock.sales-order.edit', [$farm->id, $item->id]) }}" color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Apakah Anda yakin ingin menghapus Sales Order ini?" color="red" size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="8" empty="Tidak ada data Sales Order ditemukan." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $salesOrders->links() }}
    </div>
</div>
