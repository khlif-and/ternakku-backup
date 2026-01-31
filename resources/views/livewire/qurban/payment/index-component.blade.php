<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Start Date">
            <input type="date" wire:model.live="end_date"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="End Date">

            <select wire:model.live="qurban_customer_id"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Pelanggan</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->user->name ?? $customer->id }}</option>
                @endforeach
            </select>
        </div>
        <x-button.link href="{{ route('admin.qurban.payment.create') }}" color="green">
            + Tambah Pembayaran
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'Pelanggan', 'class' => 'text-left'],
            ['label' => 'Ternak (Eartag)', 'class' => 'text-left'],
            ['label' => 'Ras', 'class' => 'text-left'],
            ['label' => 'Jumlah', 'class' => 'text-right'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($items as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $items->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->transaction_date ? date('d/m/Y', strtotime($item->transaction_date)) : '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm font-semibold text-gray-700">
                    {{ $item->qurbanCustomer->user->name ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-600">
                    {{ $item->livestock->eartag_number ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-600">
                    {{ $item->livestock->livestockBreed->name ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm text-right font-mono">
                    Rp {{ number_format($item->amount, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.qurban.payment.show', $item->id) }}"
                            color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('admin.qurban.payment.edit', $item->id) }}"
                            color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus data pembayaran ini?" color="red"
                            size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="8" empty="Tidak ada data pembayaran ditemukan." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>