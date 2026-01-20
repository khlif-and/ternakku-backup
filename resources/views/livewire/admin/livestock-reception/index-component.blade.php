<div>
    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-green-100 border border-green-400 text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-red-100 border border-red-400 text-red-700 font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search & Filter --}}
    <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="flex gap-4 w-full md:w-auto">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari eartag, RFID, jenis..."
                class="px-4 py-2 border rounded-lg text-sm w-full md:w-64">
            <select wire:model.live="perPage" class="px-4 py-2 border rounded-lg text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <a href="{{ route('admin.care-livestock.livestock-reception.create', $farm->id) }}"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg px-5 py-2 text-sm transition-all">
            + Tambah Registrasi
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Eartag</th>
                    <th class="px-6 py-4">Jenis</th>
                    <th class="px-6 py-4">Ras</th>
                    <th class="px-6 py-4">Kelamin</th>
                    <th class="px-6 py-4">Kandang</th>
                    <th class="px-6 py-4">Berat</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($receptions as $reception)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $reception->eartag_number }}</td>
                        <td class="px-6 py-4">{{ $reception->livestockType->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $reception->livestockBreed->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $reception->livestockSex->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $reception->pen->name ?? '-' }}</td>
                        <td class="px-6 py-4">{{ number_format($reception->weight, 2) }} kg</td>
                        <td class="px-6 py-4">{{ $reception->livestockReceptionH->transaction_date ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.care-livestock.livestock-reception.edit', [$farm->id, $reception->id]) }}"
                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-xs font-medium">
                                    Edit
                                </a>
                                <button wire:click="delete({{ $reception->id }})"
                                    wire:confirm="Yakin ingin menghapus registrasi ini?"
                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-xs font-medium">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data registrasi ternak.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $receptions->links() }}
    </div>
</div>
