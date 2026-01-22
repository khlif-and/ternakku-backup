<div>
    @if (session('success'))
        <x-alert.flash type="success">{{ session('success') }}</x-alert.flash>
    @endif

    @if (session('error'))
        <x-alert.flash type="error">{{ session('error') }}</x-alert.flash>
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
        <x-button.link href="{{ route('admin.care-livestock.feeding-colony.create', $farm->id) }}" color="green">
            + Tambah Pemberian Pakan
        </x-button.link>
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
                                <x-button.action href="{{ route('admin.care-livestock.feeding-colony.show', [$farm->id, $item->id]) }}" color="gray">Detail</x-button.action>
                                <x-button.action href="{{ route('admin.care-livestock.feeding-colony.edit', [$farm->id, $item->id]) }}" color="blue">Edit</x-button.action>
                                <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus data ini?" color="red" size="sm">Hapus</x-button.primary>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table.empty colspan="7" empty="Belum ada data pemberian pakan koloni." />
                @endforelse
            </tbody>
        </table>
    </div>
</div>
