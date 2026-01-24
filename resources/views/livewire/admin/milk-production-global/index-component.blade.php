<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
        </div>

        <x-button.link href="{{ route('admin.care-livestock.milk-production-global.create', $farm->id) }}" color="green">
            + Tambah Produksi
        </x-button.link>
    </div>

    <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
        @php
            $headers = [
                ['label' => 'No', 'class' => 'text-left w-12'],
                ['label' => 'No. Transaksi', 'class' => 'text-left'],
                ['label' => 'Tanggal', 'class' => 'text-left'],
                ['label' => 'Jam Perah', 'class' => 'text-left'],
                ['label' => 'Pemerah', 'class' => 'text-left'],
                ['label' => 'Kondisi', 'class' => 'text-left'],
                ['label' => 'Volume (L)', 'class' => 'text-right'],
                ['label' => 'Aksi', 'class' => 'text-center'],
            ];
        @endphp

        <x-table.wrapper :headers="$headers">
            @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-600 border-b">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900 border-b">{{ $item->transaction_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 border-b">{{ date('d/m/Y', strtotime($item->transaction_date)) }}</td>
                    <td class="px-4 py-3 text-sm font-black text-blue-600 uppercase border-b">
                        {{ date('H:i', strtotime($item->milking_time)) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 uppercase font-medium border-b">{{ $item->milker_name }}</td>
                    <td class="px-4 py-3 text-sm border-b">
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-[10px] font-black uppercase tracking-tight">
                            {{ $item->milk_condition ?: '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-black text-blue-700 text-right border-b">
                        {{ number_format($item->quantity_liters, 2, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-center border-b">
                        <div class="flex items-center justify-center gap-2">
                            <x-button.action href="{{ route('admin.care-livestock.milk-production-global.show', [$farm->id, $item->id]) }}" color="gray">
                                Detail
                            </x-button.action>
                            
                            <x-button.action href="{{ route('admin.care-livestock.milk-production-global.edit', [$farm->id, $item->id]) }}" color="blue">
                                Edit
                            </x-button.action>
                            
                            <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus data produksi ini?" color="red" size="sm">
                                Hapus
                            </x-button.primary>
                        </div>
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="8" empty="Data produksi susu belum tersedia." />
            @endforelse
        </x-table.wrapper>
    </div>
</div>