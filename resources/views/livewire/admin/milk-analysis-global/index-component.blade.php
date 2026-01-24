<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 shadow-sm">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 shadow-sm">
        </div>

        <x-button.link href="{{ route('admin.care-livestock.milk-analysis-global.create', $farm->id) }}" color="green">
            <span class="font-bold">+ Tambah Analisis</span>
        </x-button.link>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        @php
            $headers = [
                ['label' => 'No', 'class' => 'text-left w-12'],
                ['label' => 'Tanggal', 'class' => 'text-left'],
                ['label' => 'BJ', 'class' => 'text-center'],
                ['label' => 'Lemak (%)', 'class' => 'text-center'],
                ['label' => 'Protein (%)', 'class' => 'text-center'],
                ['label' => 'SNF (%)', 'class' => 'text-center'],
                ['label' => 'Status', 'class' => 'text-center'],
                ['label' => 'Aksi', 'class' => 'text-center'],
            ];
        @endphp

        <x-table.wrapper :headers="$headers">
            @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-500 border-b">
                        {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900 border-b">
                        {{ date('d/m/Y', strtotime($item->transaction_date)) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-mono text-blue-600 border-b">
                        {{ number_format($item->bj, 4, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-bold text-gray-700 border-b">
                        {{ number_format($item->fat, 2, ',', '.') }}%
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-bold text-gray-700 border-b">
                        {{ number_format($item->protein, 2, ',', '.') }}%
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-bold text-gray-700 border-b">
                        {{ number_format($item->snf, 2, ',', '.') }}%
                    </td>
                    <td class="px-4 py-3 text-sm text-center border-b">
                        <span class="px-2 py-1 {{ $item->at == '+' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} rounded text-[10px] font-black uppercase">
                            Uji AT: {{ $item->at ?: '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center border-b">
                        <div class="flex items-center justify-center gap-2">
                            <x-button.action href="{{ route('admin.care-livestock.milk-analysis-global.show', [$farm->id, $item->id]) }}" color="gray">
                                Detail
                            </x-button.action>
                            
                            <x-button.action href="{{ route('admin.care-livestock.milk-analysis-global.edit', [$farm->id, $item->id]) }}" color="blue">
                                Edit
                            </x-button.action>
                            
                            <x-button.primary type="button" 
                                wire:click="delete({{ $item->id }})" 
                                wire:confirm="Hapus data analisis tanggal {{ date('d/m/Y', strtotime($item->transaction_date)) }}?" 
                                color="red" size="sm">
                                Hapus
                            </x-button.primary>
                        </div>
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="8" empty="Data analisis susu global belum tersedia." />
            @endforelse
        </x-table.wrapper>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>