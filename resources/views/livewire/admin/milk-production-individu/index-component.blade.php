<div> <x-alert.session />

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div class="flex flex-wrap gap-3">
        <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 outline-none">
        <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 outline-none">
        
        <select wire:model.live="livestock_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 outline-none bg-white min-w-[200px]">
            <option value="">Semua Ternak</option>
            @foreach($livestocks as $livestock)
                <option value="{{ $livestock->id }}">
                    {{ $livestock->eartag_number ?? '-' }} - 
                    {{ $livestock->livestockType->name ?? '-' }} 
                    ({{ $livestock->livestockBreed->name ?? '-' }})
                </option>
            @endforeach
        </select>
    </div>

    <x-button.link href="{{ route('admin.care-livestock.milk-production-individu.create', $farm->id) }}" color="green">
        + Tambah Produksi
    </x-button.link>
</div>

<div class="bg-white rounded-lg border overflow-hidden shadow-sm">
    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-12'],
            ['label' => 'Ternak', 'class' => 'text-left'],
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
                <td class="px-4 py-3 text-sm text-gray-600 border-b">
                    {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                </td>
                <td class="px-4 py-3 text-sm border-b">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-900">{{ $item->livestock->eartag_number }}</span>
                        <span class="text-[10px] text-gray-500 uppercase">
                            {{ $item->livestock->livestockType->name ?? '-' }} 
                            ({{ $item->livestock->livestockBreed->name ?? '-' }})
                        </span>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 border-b">
                    {{ date('d/m/Y', strtotime($item->milkProductionH->transaction_date)) }}
                </td>
                <td class="px-4 py-3 text-sm font-black text-blue-600 uppercase border-b">
                    {{ date('H:i', strtotime($item->milking_time)) }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 uppercase font-medium border-b">
                    {{ $item->milker_name }}
                </td>
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
                        <x-button.action href="{{ route('admin.care-livestock.milk-production-individu.show', [$farm->id, $item->id]) }}" color="gray">
                            Detail
                        </x-button.action>
                        
                        <x-button.action href="{{ route('admin.care-livestock.milk-production-individu.edit', [$farm->id, $item->id]) }}" color="blue">
                            Edit
                        </x-button.action>
                        
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus data produksi individu ini?" color="red" size="sm">
                            Hapus
                        </x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="8" empty="Data produksi susu individu belum tersedia." />
        @endforelse
    </x-table.wrapper>
</div>

<div class="mt-4">
    {{ $items->links() }}
</div>
</div>