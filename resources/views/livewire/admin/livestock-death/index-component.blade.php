<div>
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

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm">
        </div>
        <a href="{{ route('admin.care-livestock.livestock-death.create', $farm->id) }}"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-lg transition-all">
            + Tambah Kematian
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Eartag</th>
                    <th class="px-4 py-3 text-left font-semibold">Jenis</th>
                    <th class="px-4 py-3 text-left font-semibold">Ras</th>
                    <th class="px-4 py-3 text-left font-semibold">Indikasi</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($deaths as $index => $death)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $deaths->firstItem() + $index }}</td>
                        <td class="px-4 py-3">{{ $death->transaction_date ? date('d/m/Y', strtotime($death->transaction_date)) : '-' }}</td>
                        <td class="px-4 py-3 font-medium">{{ $death->livestock?->eartag_number }}</td>
                        <td class="px-4 py-3">{{ $death->livestock?->livestockType?->name }}</td>
                        <td class="px-4 py-3">{{ $death->livestock?->livestockBreed?->name }}</td>
                        <td class="px-4 py-3">{{ Str::limit($death->indication, 30) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.care-livestock.livestock-death.edit', [$farm->id, $death->id]) }}"
                                    class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transition-all">
                                    Edit
                                </a>
                                <button wire:click="delete({{ $death->id }})"
                                    wire:confirm="Yakin ingin menghapus data kematian ini?"
                                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition-all">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            Belum ada data kematian ternak.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $deaths->links() }}
    </div>
</div>
