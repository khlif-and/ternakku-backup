<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    @php
        $headers = [
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'Ternak', 'class' => 'text-left'],
            ['label' => 'Kandang', 'class' => 'text-left'],
            ['label' => 'Berat', 'class' => 'text-right'],
            ['label' => 'Catatan', 'class' => 'text-left'],
        ];
    @endphp
    
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Penimbangan</h3>
        <x-table.wrapper :headers="$headers">
            @forelse ($reweights as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($item->livestockReweightH->transaction_date)->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">
                            {{ $item->livestock->eartag_number ?? $item->livestock->code ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->livestock->livestockType->name ?? '' }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        {{ $item->livestock->pen->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800">
                        {{ number_format($item->weight, 2, ',', '.') }} kg
                    </td>
                     <td class="px-4 py-3 text-gray-600 italic">
                        {{ $item->livestockReweightH->notes ?? '-' }}
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="5" empty="Tidak ada data penimbangan pada periode ini." />
            @endforelse
        </x-table.wrapper>

        <div class="mt-4">
            {{ $reweights instanceof \Illuminate\Pagination\LengthAwarePaginator ? $reweights->links() : '' }}
        </div>
    </div>
</div>
