<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    @php
        $headers = [
            ['label' => 'Tanggal Cek', 'class' => 'text-left'],
            ['label' => 'Ternak', 'class' => 'text-left'],
            ['label' => 'Hasil Cek', 'class' => 'text-center'],
            ['label' => 'Usia Kebuntingan', 'class' => 'text-center'],
            ['label' => 'Estimasi Lahir', 'class' => 'text-left'],
            ['label' => 'Petugas', 'class' => 'text-left'],
        ];
    @endphp
    
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat PKB</h3>
        <x-table.wrapper :headers="$headers">
            @forelse ($checks as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($item->pregnantCheck->transaction_date)->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                         <div class="font-medium text-gray-900">
                            {{ $item->reproductionCycle->livestock->eartag_number ?? $item->reproductionCycle->livestock->code ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->reproductionCycle->livestock->livestockType->name ?? '' }} 
                            • {{ $item->reproductionCycle->livestock->pen->name ?? '-' }}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($item->status == 'PREGNANT')
                            <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">BUNTING</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold">TIDAK BUNTING</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($item->pregnant_age)
                            {{ $item->pregnant_age }} Hari
                        @else
                            -
                        @endif
                    </td>
                     <td class="px-4 py-3">
                        @if($item->estimated_birth_date)
                            {{ \Carbon\Carbon::parse($item->estimated_birth_date)->format('d M Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $item->officer_name ?? '-' }}
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="6" empty="Tidak ada data cek kehamilan pada periode ini." />
            @endforelse
        </x-table.wrapper>

        <div class="mt-4">
            {{ $checks instanceof \Illuminate\Pagination\LengthAwarePaginator ? $checks->links() : '' }}
        </div>
    </div>
</div>
