<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    @php
        $headers = [
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'Ternak', 'class' => 'text-left'],
            ['label' => 'Kandang', 'class' => 'text-left'],
            ['label' => 'Bangsa', 'class' => 'text-left'],
            ['label' => 'Pejantan', 'class' => 'text-left'],
            ['label' => 'Keterangan', 'class' => 'text-left'],
        ];
    @endphp
    
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Inseminasi Alami</h3>
        <x-table.wrapper :headers="$headers">
            @forelse ($inseminations as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($item->insemination->date ?? now())->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">
                            {{ $item->reproductionCycle->livestock->eartag ?? $item->reproductionCycle->livestock->code ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->reproductionCycle->livestock->livestockType->name ?? '' }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        {{ $item->reproductionCycle->livestock->pen->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $item->reproductionCycle->livestock->livestockBreed->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $item->male_livestock_id ? ($item->maleLivestock->eartag ?? $item->maleLivestock->code ?? 'Unknown') : '-' }}
                    </td>
                   <td class="px-4 py-3 text-gray-600 italic">
                        {{ $item->note ?? '-' }}
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="6" empty="Tidak ada data inseminasi alami pada periode ini." />
            @endforelse
        </x-table.wrapper>

        <div class="mt-4">
            {{ $inseminations instanceof \Illuminate\Pagination\LengthAwarePaginator ? $inseminations->links() : '' }}
        </div>
    </div>
</div>
