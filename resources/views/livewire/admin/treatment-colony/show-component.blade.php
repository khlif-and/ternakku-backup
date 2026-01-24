<div>
    <x-alert.session />

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Sisi Kiri: Ringkasan & Info Utama --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg border p-4 space-y-3">
                <div>
                    <div class="text-xs text-gray-500">Tanggal</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $treatmentColony->treatmentH?->transaction_date ? date('d M Y', strtotime($treatmentColony->treatmentH->transaction_date)) : '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Kandang</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $treatmentColony->pen?->name ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Penyakit</div>
                    <div class="text-sm">
                        <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded text-xs font-semibold border border-red-100">
                            {{ $treatmentColony->disease?->name ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Kapasitas</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ number_format($treatmentColony->pen?->capacity ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Populasi</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ number_format($treatmentColony->pen?->population ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Total Biaya</div>
                        <div class="text-sm font-semibold text-gray-800">
                            Rp {{ number_format($treatmentColony->total_cost ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">Rata-rata / Ekor</div>
                        <div class="text-sm font-semibold text-gray-800">
                            Rp {{ number_format($treatmentColony->average_cost ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Jumlah Ternak Diobati</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ number_format($treatmentColony->total_livestock ?? ($treatmentColony->livestocks?->count() ?? 0), 0, ',', '.') }} ekor
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500">Catatan</div>
                    <div class="text-sm text-gray-800">
                        {{ $treatmentColony->notes ?: '-' }}
                    </div>
                </div>

                <x-button.primary wire:click="delete" wire:confirm="Yakin ingin menghapus data treatment ini?" color="red" class="w-full">
                    Hapus
                </x-button.primary>
            </div>

            {{-- Anggota Koloni --}}
            <div class="bg-white rounded-lg border p-4">
                <div class="mb-3 font-semibold text-gray-700">Anggota Koloni yang Diobati</div>
                @if($treatmentColony->livestocks?->count())
                    <ul class="space-y-2 max-h-60 overflow-auto pr-1">
                        @foreach($treatmentColony->livestocks as $ls)
                            <li class="text-sm text-gray-700 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                <span class="font-semibold">{{ $ls->livestock?->eartag_number ?? $ls->livestock?->rfid_number ?? '-' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-sm text-gray-500 italic">Tidak ada data anggota koloni.</div>
                @endif
            </div>
        </div>

        {{-- Sisi Kanan: Detail Item Obat & Tindakan --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Tabel Item Obat --}}
            <div class="bg-white rounded-lg border overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Item Obat-obatan
                </div>
                @php
                    $medicineHeaders = [
                        ['label' => 'No', 'class' => 'text-left w-12'],
                        ['label' => 'Nama Obat', 'class' => 'text-left'],
                        ['label' => 'Satuan', 'class' => 'text-left'],
                        ['label' => 'Qty/Unit', 'class' => 'text-right'],
                        ['label' => 'Harga/Unit', 'class' => 'text-right'],
                        ['label' => 'Total', 'class' => 'text-right'],
                    ];
                @endphp
                <x-table.wrapper :headers="$medicineHeaders">
                    @forelse($treatmentColony->treatmentColonyMedicineItems as $i => $mi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ $mi->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $mi->unit }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($mi->qty_per_unit, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp {{ number_format($mi->price_per_unit, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right font-semibold">Rp {{ number_format($mi->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <x-table.empty colspan="6" empty="Belum ada item obat." />
                    @endforelse
                </x-table.wrapper>
            </div>

            {{-- Tabel Item Tindakan --}}
            <div class="bg-white rounded-lg border overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Tindakan / Jasa Medis
                </div>
                @php
                    $actionHeaders = [
                        ['label' => 'No', 'class' => 'text-left w-12'],
                        ['label' => 'Nama Tindakan', 'class' => 'text-left'],
                        ['label' => 'Biaya', 'class' => 'text-right'],
                    ];
                @endphp
                <x-table.wrapper :headers="$actionHeaders">
                    @forelse($treatmentColony->treatmentColonyTreatmentItems as $j => $ti)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $j + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ $ti->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 text-right font-semibold">Rp {{ number_format($ti->cost, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <x-table.empty colspan="3" empty="Belum ada tindakan medis." />
                    @endforelse

                    <x-slot:footer>
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">GRAND TOTAL (OBAT + TINDAKAN)</td>
                            <td class="px-4 py-3 text-sm font-bold text-blue-700 text-right bg-blue-50">
                                Rp {{ number_format($treatmentColony->total_cost ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </x-slot:footer>
                </x-table.wrapper>
            </div>

        </div>
    </div>
</div>