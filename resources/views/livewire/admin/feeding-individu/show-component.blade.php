<div>
    <x-alert.session />

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg border p-4 space-y-3">
                <div>
                    <div class="text-xs text-gray-500">Tanggal</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $feedingIndividu->feedingH?->transaction_date ? date('d M Y', strtotime($feedingIndividu->feedingH->transaction_date)) : '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Ternak</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $feedingIndividu->livestock?->eartag ?? $feedingIndividu->livestock?->eartag_number ?? '-' }} - 
                        {{ $feedingIndividu->livestock?->name ?? $feedingIndividu->livestock?->display_name ?? '-' }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Breed</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $feedingIndividu->livestock?->livestockBreed?->name ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Total Biaya</div>
                        <div class="text-sm font-semibold text-gray-800">
                            Rp {{ number_format($feedingIndividu->total_cost ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Catatan</div>
                    <div class="text-sm text-gray-800">
                        {{ $feedingIndividu->notes ?: '-' }}
                    </div>
                </div>

                <x-button.primary wire:click="delete" wire:confirm="Yakin ingin menghapus data pemberian pakan ini?" color="red" class="w-full">
                    Hapus
                </x-button.primary>
            </div>
        </div>

        <div class="lg:col-span-2">
            @php
                $headers = [
                    ['label' => 'No', 'class' => 'text-left w-12'],
                    ['label' => 'Tipe', 'class' => 'text-left'],
                    ['label' => 'Nama', 'class' => 'text-left'],
                    ['label' => 'Qty (kg)', 'class' => 'text-right'],
                    ['label' => 'Harga/kg', 'class' => 'text-right'],
                    ['label' => 'Total', 'class' => 'text-right'],
                ];
            @endphp
            
            <div class="bg-white rounded-lg border overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">Item Pakan</div>
                <x-table.wrapper :headers="$headers">
                    @forelse($feedingIndividu->feedingIndividuItems as $i => $it)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @switch($it->type)
                                    @case('forage') Hijauan @break
                                    @case('concentrate') Konsentrat @break
                                    @case('feed_material') Bahan Pakan @break
                                    @default {{ $it->type }}
                                @endswitch
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $it->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($it->qty_kg, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp {{ number_format($it->price_per_kg, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp {{ number_format($it->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <x-table.empty colspan="6" empty="Belum ada item pakan." />
                    @endforelse

                    <x-slot:footer>
                        @if($feedingIndividu->feedingIndividuItems?->count())
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">TOTAL</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800 text-right">
                                    Rp {{ number_format($feedingIndividu->total_cost ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    </x-slot:footer>
                </x-table.wrapper>
            </div>
        </div>
    </div>
</div>
