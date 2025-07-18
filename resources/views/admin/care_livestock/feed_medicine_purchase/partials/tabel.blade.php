<div class="flex flex-col sm:flex-row items-center justify-between px-8 py-4 gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Pembelian Pakan / Obat</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar semua data Pembelian Pakan / Obat ternak di peternakan.</p>
    </div>
    <div class="flex-shrink-0">
        <a href="{{ route('admin.care-livestock.feed-medicine-purchase.create', $farm->id) }}"
            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
            <svg xmlns="http:
                <path fill-rule="evenodd"
                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                clip-rule="evenodd" />
            </svg>
            Tambah Pembelian Pakan / Obat
        </a>
    </div>
</div>

@if (session('success'))
    <div class="px-8 pt-2">
        <div class="px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="px-8 py-4">
    <form method="GET" action="{{ route('admin.care-livestock.feed-medicine-purchase.index', $farm->id) }}"
        class="relative w-full max-w-xs">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http:
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
            class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:outline-none transition"
            placeholder="Cari berdasarkan supplier, catatan...">
    </form>
</div>

<div class="flex flex-col mt-2">
    <div class="-my-2 overflow-x-auto">
        <div class="py-2 align-middle inline-block min-w-full px-8">
            <div class="shadow-sm overflow-hidden border-b border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @php
                                $headers = [
                                    'No',
                                    'Tanggal',
                                    'Supplier',
                                    'Total Item',
                                    'Total Harga',
                                    'Catatan',
                                    'Aksi',
                                ];
                            @endphp
                            @foreach ($headers as $header)
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($data as $index => $purchase)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ \Carbon\Carbon::parse($purchase->transaction_date)->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $purchase->supplier ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $purchase->feedMedicinePurchaseItem->count() }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    Rp{{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800" title="{{ $purchase->notes }}">
                                    {{ \Illuminate\Support\Str::limit($purchase->notes, 20, '...') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.care-livestock.feed-medicine-purchase.edit', [$farm->id, $purchase->id]) }}"
                                            class="text-red-600 hover:text-red-900 p-2 hover:bg-gray-100 rounded-full"
                                            title="Edit">
                                            <svg xmlns="http:
                                                viewBox="0
                                                0 20 20">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <form
                                            action="{{ route('admin.care-livestock.feed-medicine-purchase.destroy', [$farm->id, $purchase->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 p-2 hover:bg-gray-100 rounded-full"
                                                title="Hapus">
                                                <svg xmlns="http:
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500">
                                    Belum ada data pembelian pakan/obat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
