{{-- Header --}}
<div class="flex flex-col sm:flex-row items-center justify-between px-8 py-4 gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Klasifikasi Ternak</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar ternak dan klasifikasinya saat ini.</p>
    </div>
</div>

{{-- Alert Sukses atau Error --}}
@if (session('success'))
    <div class="px-8 pt-2">
        <div class="px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif
@if (session('error'))
    <div class="px-8 pt-2">
        <div class="px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            {{ session('error') }}
        </div>
    </div>
@endif

{{-- Filter --}}
<div class="px-8 py-4">
    <form method="GET" action="{{ route('admin.care-livestock.classification.index', $farm->id) }}"
          class="flex flex-wrap items-center gap-4">
        <div>
            <label for="eartag_number" class="text-xs text-gray-600">Cari Eartag</label>
            <input type="text" name="eartag_number" id="eartag_number" value="{{ request('eartag_number') }}"
                   class="block w-full border border-gray-300 rounded-lg text-sm bg-white text-gray-900 px-3 py-2" placeholder="Masukkan eartag...">
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
                                $headers = ['No', 'ID Ternak (Eartag)', 'Klasifikasi Saat Ini', 'Aksi'];
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
                        @forelse($livestocks as $index => $livestock)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-4 text-sm text-gray-500">{{ $livestocks->firstItem() + $index }}</td>
                                <td class="px-4 py-4 text-sm text-gray-800 font-medium">
                                    {{ $livestock->eartag_number ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-800">
                                    {{-- DIUBAH: Menggunakan nama relasi yang benar 'livestockClassification' --}}
                                    {{ $livestock->livestockClassification->name ?? 'Belum Diklasifikasi' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-800">
                                    <a href="{{ route('admin.care-livestock.classification.edit', ['farm_id' => $farm->id, 'id' => $livestock->id]) }}"
                                       class="text-red-600 hover:text-red-900 font-medium">
                                       Ubah
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-500">
                                    Tidak ada data ternak yang ditemukan.
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
@if ($livestocks instanceof \Illuminate\Pagination\LengthAwarePaginator && $livestocks->count())
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-8 py-4 border-t">
    <div class="text-sm text-gray-700">
        Menampilkan
        <span class="font-medium">{{ $livestocks->firstItem() }}</span>
        sampai
        <span class="font-medium">{{ $livestocks->lastItem() }}</span>
        dari
        <span class="font-medium">{{ $livestocks->total() }}</span>
        hasil
    </div>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-8 py-4">
        <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
            {{ $livestocks->withQueryString()->links('vendor.pagination.modern-pagination') }}
        </div>
    </div>
</div>
@endif
