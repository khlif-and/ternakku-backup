@php($farmId = request()->route('farm_id'))
<div class="flex flex-col sm:flex-row items-center justify-between px-8 py-4 gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Artificial Inseminasi</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar semua data artificial inseminasi di peternakan.</p>
    </div>
    <div class="flex-shrink-0">
        <a href="{{ route('admin.care_livestock.artificial_inseminasi.create', ['farm_id' => $farmId]) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow-sm transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M12 4.5a.75.75 0 01.75.75v6h6a.75.75 0 010 1.5h-6v6a.75.75 0 01-1.5 0v-6h-6a.75.75 0 010-1.5h6v-6A.75.75 0 0112 4.5z" clip-rule="evenodd"/>
            </svg>
            Tambah Artificial Inseminasi
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
    <form method="GET" action="{{ route('admin.care_livestock.artificial_inseminasi.index', ['farm_id' => $farmId]) }}"
          class="relative w-full max-w-xs">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 21l-4.35-4.35m1.6-5.4a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
               class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm bg-white text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
               placeholder="Cari eartag, nama ternak, petugas...">
    </form>
</div>

<div class="flex flex-col mt-2">
    <div class="-my-2 overflow-x-auto">
        <div class="py-2 align-middle inline-block min-w-full px-8">
            <div class="shadow-sm overflow-hidden border-b border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Eartag</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Ternak</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Tindakan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Petugas</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Biaya</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($items as $index => $ai)
                            @php($lv = optional($ai->reproductionCycle)->livestock)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>

                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ optional($ai->insemination)->transaction_date
                                        ? \Carbon\Carbon::parse($ai->insemination->transaction_date)->format('d M Y')
                                        : '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{
                                        optional($lv)->eartag
                                        ?? optional($lv)->eartag_number
                                        ?? optional($lv)->ear_tag
                                        ?? optional($lv)->tag
                                        ?? optional($lv)->code
                                        ?? optional($lv)->rfid_number
                                        ?? '-'
                                    }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{
                                        optional($lv)->name
                                        ?? optional($lv)->nama
                                        ?? optional($lv)->display_name
                                        ?? optional($lv)->nickname
                                        ?? '-'
                                    }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $ai->action_time ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $ai->officer_name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-800">
                                    Rp {{ number_format($ai->cost ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-800" title="{{ optional($ai->insemination)->notes ?? $ai->notes }}">
                                    {{ \Illuminate\Support\Str::limit(optional($ai->insemination)->notes ?? $ai->notes, 20, '...') }}
                                </td>

                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.care_livestock.artificial_inseminasi.edit', ['farm_id' => $farmId, 'id' => $ai->id]) }}"
                                           class="text-blue-600 hover:text-blue-900 p-2 hover:bg-gray-100 rounded-full"
                                           title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                 fill="currentColor" class="w-5 h-5">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-8.95 8.95a2 2 0 01-.878.502l-3.3.943a.5.5 0 01-.62-.62l.943-3.3a2 2 0 01.502-.878l8.95-8.95z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.care_livestock.artificial_inseminasi.destroy', ['farm_id' => $farmId, 'id' => $ai->id]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 p-2 hover:bg-gray-100 rounded-full"
                                                    title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
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
                                <td colspan="9" class="text-center py-10 text-gray-500">
                                    Belum ada data artificial inseminasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
