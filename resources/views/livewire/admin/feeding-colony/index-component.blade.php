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
            <select wire:model.live="pen_id" class="px-4 py-2 border rounded-lg text-sm">
                <option value="">Semua Kandang</option>
                @foreach($pens as $pen)
                    <option value="{{ $pen->id }}">{{ $pen->name }}</option>
                @endforeach
            </select>
        </div>
        <a href="{{ route('admin.care-livestock.feeding-colony.create', $farm->id) }}"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-lg transition-all">
            + Tambah Pemberian Pakan
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Kandang</th>
                    <th class="px-4 py-3 text-left font-semibold">Jumlah Ternak</th>
                    <th class="px-4 py-3 text-left font-semibold">Total Biaya</th>
                    <th class="px-4 py-3 text-left font-semibold">Rata-rata/Ekor</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $item->feedingH?->transaction_date ? date('d/m/Y', strtotime($item->feedingH->transaction_date)) : '-' }}</td>
                        <td class="px-4 py-3 font-medium">{{ $item->pen?->name }}</td>
                        <td class="px-4 py-3">{{ $item->total_livestock }} ekor</td>
                        <td class="px-4 py-3">Rp {{ number_format($item->total_cost, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($item->average_cost, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.care-livestock.feeding-colony.show', [$farm->id, $item->id]) }}"
                                    class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs rounded-lg transition-all">
                                    Detail
                                </a>
                                <a href="{{ route('admin.care-livestock.feeding-colony.edit', [$farm->id, $item->id]) }}"
                                    class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transition-all">
                                    Edit
                                </a>
                                <button wire:click="delete({{ $item->id }})"
                                    wire:confirm="Yakin ingin menghapus data ini?"
                                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition-all">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            Belum ada data pemberian pakan koloni.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
