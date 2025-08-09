@php($farmId = request()->route('farm_id'))
<div class="px-8 py-4 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Detail Treatment Koloni</h1>
        <p class="mt-1 text-sm text-gray-500">Ringkasan transaksi, kandang, penyakit, serta item obat & tindakan.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.care-livestock.treatment-colony.edit', ['farm_id' => $farmId, 'id' => $treatmentColony->id]) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
           Edit
        </a>
        <a href="{{ route('admin.care-livestock.treatment-colony.index', ['farm_id' => $farmId]) }}"
           class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
           Kembali
        </a>
    </div>
</div>

@if (session('success'))
    <div class="px-8">
        <div class="px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="px-8 py-4 grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg border p-4 space-y-3">
            <div>
                <div class="text-xs text-gray-500">Tanggal</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional($treatmentColony->treatmentH)->transaction_date
                        ? \Carbon\Carbon::parse($treatmentColony->treatmentH->transaction_date)->format('d M Y')
                        : '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Kandang</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional($treatmentColony->pen)->name ?? '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500">Penyakit</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional($treatmentColony->disease)->name ?? '-' }}
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

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <div class="text-xs text-gray-500">Jumlah Ternak</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $treatmentColony->total_livestock ?? 0 }} ekor
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Catatan</div>
                    <div class="text-sm text-gray-800">
                        {{ $treatmentColony->notes ?: '-' }}
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.care-livestock.treatment-colony.destroy', ['farm_id' => $farmId, 'id' => $treatmentColony->id]) }}"
                  method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" class="pt-2">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold rounded-lg px-4 py-2 text-sm transition-all">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        {{-- Item Obat --}}
        <div class="bg-white rounded-lg border overflow-hidden">
            <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">Item Obat</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Obat</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Satuan</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Qty/Unit</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Harga/Unit</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($treatmentColony->treatmentColonyMedicineItems as $i => $mi)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $mi->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $mi->unit }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($mi->qty_per_unit, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp {{ number_format($mi->price_per_unit, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp {{ number_format($mi->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada item obat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Item Tindakan --}}
        <div class="bg-white rounded-lg border overflow-hidden">
            <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">Tindakan</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Tindakan</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($treatmentColony->treatmentColonyTreatmentItems as $j => $ti)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $j + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $ti->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp {{ number_format($ti->cost, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500">Belum ada tindakan.</td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if(($treatmentColony->treatmentColonyMedicineItems ?? collect())->count()
                        || ($treatmentColony->treatmentColonyTreatmentItems ?? collect())->count())
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">TOTAL</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800 text-right">
                                    Rp {{ number_format($treatmentColony->total_cost ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">RATA-RATA / EKOR</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800 text-right">
                                    Rp {{ number_format($treatmentColony->average_cost ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- (Opsional) Daftar Ternak yang Terkait --}}
        @if(method_exists($treatmentColony, 'livestocks') && ($treatmentColony->livestocks ?? collect())->count())
            <div class="bg-white rounded-lg border overflow-hidden">
                <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">Ternak Dalam Kandang Ini</div>
                <div class="p-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach($treatmentColony->livestocks as $tl)
                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700 border">
                                {{ optional($tl->livestock)->eartag
                                    ?? optional($tl->livestock)->eartag_number
                                    ?? optional($tl->livestock)->ear_tag
                                    ?? optional($tl->livestock)->tag
                                    ?? optional($tl->livestock)->code
                                    ?? optional($tl->livestock)->rfid_number
                                    ?? '—' }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
