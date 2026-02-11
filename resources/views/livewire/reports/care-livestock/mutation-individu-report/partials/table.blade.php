<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    @php
        $headers = [
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Ternak', 'class' => 'text-left'],
            ['label' => 'Dari Kandang', 'class' => 'text-left'],
            ['label' => 'Ke Kandang', 'class' => 'text-left'],
            ['label' => 'Keterangan', 'class' => 'text-left'],
        ];
    @endphp
    
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Mutasi</h3>
        <x-table.wrapper :headers="$headers">
            @forelse ($mutations as $mutation)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($mutation->mutationH->transaction_date ?? now())->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 font-medium">
                        {{ $mutation->mutationH->transaction_number ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $mutation->livestock->eartag ?? $mutation->livestock->code ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $mutation->livestock->livestockType->name ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-red-50 text-red-700 rounded text-xs border border-red-100">
                            {{ $mutation->fromPen->name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs border border-blue-100">
                            {{ $mutation->toPen->name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 italic">
                        {{ $mutation->notes ?? '-' }}
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="6" empty="Tidak ada data mutasi pada periode ini." />
            @endforelse
        </x-table.wrapper>
    </div>
</div>
