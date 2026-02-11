<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    @php
        $headers = [
            ['label' => 'Tanggal & Indukan', 'class' => 'text-left'],
            ['label' => 'Detail Kelahiran (Anak)', 'class' => 'text-left'],
            ['label' => 'Total Biaya', 'class' => 'text-right'],
            ['label' => 'Catatan', 'class' => 'text-left'],
        ];
    @endphp
    
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Kelahiran</h3>
        <x-table.wrapper :headers="$headers">
            @forelse ($births as $item)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">
                             {{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}
                        </div>
                        <div class="mt-1 font-medium text-emerald-700">
                             Induk: {{ $item->reproductionCycle->livestock->eartag_number ?? $item->reproductionCycle->livestock->code ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-500">
                             {{ $item->reproductionCycle->livestock->pen->name ?? '-' }}
                        </div>
                         <div class="text-xs text-gray-500 mt-1">
                             Petugas: {{ $item->officer_name ?? '-' }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <ul class="text-sm space-y-2">
                            @forelse($item->livestockBirthD as $detail)
                                <li class="flex items-center gap-2 border-b border-gray-100 pb-1 last:border-0 last:pb-0">
                                    <span class="px-1.5 py-0.5 rounded text-xs font-bold {{ $detail->status == 'alive' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $detail->status == 'alive' ? 'HIDUP' : 'MATI' }}
                                    </span>
                                    <span class="text-gray-700">
                                         #{{ $detail->birth_order }} 
                                         ({{ $detail->livestock_sex_id == 1 ? 'Jantan' : ($detail->livestock_sex_id == 2 ? 'Betina' : '?') }})
                                    </span>
                                    <span class="text-gray-500 text-xs">
                                        {{ number_format($detail->weight, 2, ',', '.') }} kg
                                    </span>
                                    @if($detail->disease_id)
                                        <span class="text-red-500 text-xs italic">Sakit</span>
                                    @endif
                                </li>
                            @empty
                                <li class="text-gray-500 italic text-xs">Tidak ada data detail anak.</li>
                            @endforelse
                        </ul>
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800">
                        Rp {{ number_format($item->cost, 0, ',', '.') }}
                    </td>
                     <td class="px-4 py-3 text-gray-600 italic text-sm">
                        {{ $item->notes ?? '-' }}
                         @if($item->status == 'ABORTUS')
                             <span class="block text-red-600 font-bold mt-1 text-xs">ABORTUS</span>
                         @endif
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="4" empty="Tidak ada data kelahiran pada periode ini." />
            @endforelse
        </x-table.wrapper>

        <div class="mt-4">
            {{ $births instanceof \Illuminate\Pagination\LengthAwarePaginator ? $births->links() : '' }}
        </div>
    </div>
</div>
