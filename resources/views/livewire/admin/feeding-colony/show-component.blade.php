<div>
    <x-alert.session />

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg border p-4 space-y-3">
                <div>
                    <div class="text-xs text-gray-500">Tanggal</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $feedingColony->feedingH?->transaction_date ? date('d M Y', strtotime($feedingColony->feedingH->transaction_date)) : '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Kandang</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $feedingColony->pen?->name ?? '-' }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Kapasitas</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ number_format($feedingColony->pen?->capacity ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Populasi</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ number_format($feedingColony->pen?->population ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Total Biaya</div>
                        <div class="text-sm font-semibold text-gray-800">
                            Rp {{ number_format($feedingColony->total_cost ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Rata-rata / Ekor</div>
                        <div class="text-sm font-semibold text-gray-800">
                            Rp {{ number_format($feedingColony->average_cost ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Jumlah Ternak</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ number_format($feedingColony->total_livestock ?? ($feedingColony->livestocks?->count() ?? 0), 0, ',', '.') }} ekor
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Catatan</div>
                    <div class="text-sm text-gray-800">
                        {{ $feedingColony->notes ?: '-' }}
                    </div>
                </div>

                <x-button.primary wire:click="delete" wire:confirm="Yakin ingin menghapus data pemberian pakan ini?" color="red" class="w-full">
                    Hapus
                </x-button.primary>
            </div>

            <div class="bg-white rounded-lg border p-4">
                <div class="mb-3 font-semibold text-gray-700">Anggota Koloni</div>
                @if($feedingColony->livestocks?->count())
                    <ul class="space-y-2 max-h-60 overflow-auto pr-1">
                        @foreach($feedingColony->livestocks as $ls)
                            <li class="text-sm text-gray-700">
                                <span class="font-semibold">{{ $ls->eartag_number ?? $ls->rfid_number ?? '-' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-sm text-gray-500">Tidak ada data anggota koloni.</div>
                @endif
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
                    @forelse($feedingColony->feedingColonyItems as $i => $it)
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
                        @if($feedingColony->feedingColonyItems?->count())
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">TOTAL</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800 text-right">
                                    Rp {{ number_format($feedingColony->total_cost ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    </x-slot:footer>
                </x-table.wrapper>
            </div>
        </div>
    </div>
</div>
