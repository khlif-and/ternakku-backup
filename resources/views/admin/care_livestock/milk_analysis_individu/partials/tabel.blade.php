{{-- Header dan Tombol Aksi Utama --}}
<div class="flex flex-col sm:flex-row items-center justify-between px-8 py-4 gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Analisis Susu Individu</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar hasil analisis susu per ternak di peternakan.</p>
    </div>
    <div class="flex-shrink-0">
        <a href="{{ route('admin.care-livestock.milk-analysis-individu.create', $farm->id) }}"
           class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 S0 011-1z"
                      clip-rule="evenodd" />
            </svg>
            Tambah Analisis Susu Individu
        </a>
    </div>
</div>

{{-- Alert Sukses --}}
@if (session('success'))
    <div class="px-8 pt-2">
        <div class="px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

{{-- Filter --}}
<div class="px-8 py-4">
    <form method="GET" action="{{ route('admin.care-livestock.milk-analysis-individu.index', $farm->id) }}"
          class="flex flex-wrap items-center gap-4">

        {{-- Filter Ternak (PENTING untuk Individu) --}}
        <div>
            <label for="livestock_id" class="text-xs text-gray-600">Pilih Ternak</label>
            <select name="livestock_id" id="livestock_id" class="block w-full border border-gray-300 rounded-lg text-sm bg-white text-gray-900 px-3 py-2">
                <option value="">Semua Ternak</option>
                @foreach($livestocks as $livestock)
                    <option value="{{ $livestock->id }}" {{ request('livestock_id') == $livestock->id ? 'selected' : '' }}>
                        {{ $livestock->eartag_number }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Tanggal --}}
        <div>
            <label for="start_date" class="text-xs text-gray-600">Mulai</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                   class="block w-full border border-gray-300 rounded-lg text-sm bg-white text-gray-900 px-3 py-2">
        </div>
        <div>
            <label for="end_date" class="text-xs text-gray-600">Sampai</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                   class="block w-full border border-gray-300 rounded-lg text-sm bg-white text-gray-900 px-3 py-2">
        </div>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all mt-6">Filter</button>
    </form>
</div>

{{-- Kontainer Tabel --}}
<div class="flex flex-col mt-2">
    <div class="-my-2 overflow-x-auto">
        <div class="py-2 align-middle inline-block min-w-full px-8">
            <div class="shadow-sm overflow-hidden border-b border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @php
                                // Menambahkan kolom 'ID Ternak'
                                $headers = [
                                    'No', 'ID Ternak', 'Tanggal', 'BJ', 'AT', 'AB', 'MBRT', 'Air (%)', 'Protein (%)', 'Lemak (%)', 'SNF', 'TS', 'RZN', 'Catatan', 'Aksi'
                                ];
                            @endphp
                            @foreach ($headers as $header)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Menggunakan variabel $analyses dari controller --}}
                        @forelse($analyses as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-4 text-sm text-gray-500">{{ $analyses->firstItem() + $index }}</td>
                                {{-- Menampilkan data ID Ternak --}}
                                <td class="px-4 py-4 text-sm text-gray-800 font-medium">
                                    {{ $item->livestock->eartag_number ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-800">
                                    {{ \Carbon\Carbon::parse($item->milkAnalysisH->transaction_date)->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->bj ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->at ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->ab ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->mbrt ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->a_water ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->protein ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->fat ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->snf ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->ts ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800">{{ $item->rzn ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800" title="{{ $item->notes }}">
                                    {{ \Illuminate\Support\Str::limit($item->notes, 20, '...') }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.care-livestock.milk-analysis-individu.edit', [$farm->id, $item->id]) }}"
                                           class="text-red-600 hover:text-red-900 p-2 hover:bg-gray-100 rounded-full"
                                           title="Edit">
                                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                      d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <form
                                            action="{{ route('admin.care-livestock.milk-analysis-individu.destroy', [$farm->id, $item->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 p-2 hover:bg-gray-100 rounded-full"
                                                title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                     fill="currentColor" viewBox="0 0 20 20">
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
                                <td colspan="15" class="text-center py-10 text-gray-500">
                                    Belum ada data analisis susu individu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Footer Tabel dan Paginasi --}}
@if ($analyses instanceof \Illuminate\Pagination\LengthAwarePaginator && $analyses->count())
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-8 py-4">
    <div class="text-sm text-gray-700">
        Menampilkan
        <span class="font-medium">{{ $analyses->firstItem() }}</span>
        sampai
        <span class="font-medium">{{ $analyses->lastItem() }}</span>
        dari
        <span class="font-medium">{{ $analyses->total() }}</span>
        hasil
    </div>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-8 py-4">
        <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
            {{ $analyses->withQueryString()->links('vendor.pagination.modern-pagination') }}
        </div>
    </div>
</div>
@endif
