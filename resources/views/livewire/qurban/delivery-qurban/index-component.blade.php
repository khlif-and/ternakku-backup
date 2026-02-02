<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Start Date">
            <input type="date" wire:model.live="end_date"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="End Date">

            <select wire:model.live="status" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Status</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>

            <select wire:model.live="driver_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>
        <x-button.link href="{{ route('admin.qurban.qurban_delivery.create') }}" color="green">
            + Tambah Instruksi Pengiriman
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Tgl Pengiriman', 'class' => 'text-left'],
            ['label' => 'Driver', 'class' => 'text-left'],
            ['label' => 'Armada', 'class' => 'text-left'],
            ['label' => 'Status', 'class' => 'text-center'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($items as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $items->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm font-semibold">{{ $item->transaction_number }}</td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->delivery_date ? date('d/m/Y', strtotime($item->delivery_date)) : '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->driver->name ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->fleet->name ?? '-' }} ({{ $item->fleet->police_number ?? '-' }})
                </td>
                <td class="px-4 py-3 border-b text-sm text-center">
                    @php
                        $statusColors = [
                            'scheduled' => 'bg-gray-100 text-gray-800',
                            'ready_to_deliver' => 'bg-yellow-100 text-yellow-800',
                            'in_delivery' => 'bg-blue-100 text-blue-800',
                            'delivered' => 'bg-green-100 text-green-800',
                        ];
                        $color = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $color }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    </span>
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.qurban.qurban_delivery.show', $item->id) }}"
                            color="gray">Detail</x-button.action>

                        @if($item->status === 'scheduled')
                            <x-button.action href="{{ route('admin.qurban.qurban_delivery.edit', $item->id) }}"
                                color="blue">Proses</x-button.action>
                            <x-button.primary type="button" wire:click="delete({{ $item->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus instruksi pengiriman ini?" color="red"
                                size="sm">Hapus</x-button.primary>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="7" empty="Tidak ada instruksi pengiriman ditemukan." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>