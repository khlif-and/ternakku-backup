@php($farmId = request()->route('farm_id'))
<div class="px-8 py-4 flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Detail Pemberian Pakan Individu</h1>
        <p class="mt-1 text-sm text-gray-500">Ringkasan transaksi & item pakan.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.care-livestock.feeding-individu.edit', ['farm_id' => $farmId, 'id' => $feedingIndividu->id]) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
           Edit
        </a>
        <a href="{{ route('admin.care-livestock.feeding-individu.index', ['farm_id' => $farmId]) }}"
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

<div class="px-8 py-4 grid md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-lg border p-4 space-y-3">
            <div>
                <div class="text-xs text-gray-500">Tanggal</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional($feedingIndividu->feedingH)->transaction_date
                        ? \Carbon\Carbon::parse($feedingIndividu->feedingH->transaction_date)->format('d M Y')
                        : '-' }}
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Eartag</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional($feedingIndividu->livestock)->eartag ?? '-' }}
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Nama Ternak</div>
                <div class="text-sm font-semibold text-gray-800">
                    {{ optional($feedingIndividu->livestock)->name ?? '-' }}
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Total Biaya</div>
                <div class="text-sm font-semibold text-gray-800">
                    Rp {{ number_format($feedingIndividu->total_cost ?? 0, 0, ',', '.') }}
                </div>
            </div>
            <div>
                <div class="text-xs text-gray-500">Catatan</div>
                <div class="text-sm text-gray-800">
                    {{ $feedingIndividu->notes ?: '-' }}
                </div>
            </div>

            <form action="{{ route('admin.care-livestock.feeding-individu.destroy', ['farm_id' => $farmId, 'id' => $feedingIndividu->id]) }}"
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

    <div class="md:col-span-2">
        <div class="bg-white rounded-lg border overflow-hidden">
            <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-700">Item Pakan</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tipe</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Qty (kg)</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Harga/kg</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($feedingIndividu->feedingIndividuItems as $i => $it)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $it->type }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $it->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($it->qty_kg, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp {{ number_format($it->price_per_kg, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">Rp {{ number_format($it->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada item pakan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
