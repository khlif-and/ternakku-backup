<div>
    @if (session('success'))
        <x-alert.flash type="success">{{ session('success') }}</x-alert.flash>
    @endif

    @if (session('error'))
        <x-alert.flash type="error">{{ session('error') }}</x-alert.flash>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <x-filter.search wire:model.live.debounce.300ms="search" placeholder="Cari eartag, RFID, jenis..." />
            <x-filter.per-page wire:model.live="perPage" />
        </div>
        <x-button.link href="{{ route('admin.care-livestock.livestock-reception.create', $farm->id) }}" color="green">
            + Tambah Registrasi
        </x-button.link>
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
                    <th class="px-4 py-3 text-left font-semibold">Kandang</th>
                    <th class="px-4 py-3 text-left font-semibold">Berat</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($receptions as $index => $reception)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $receptions->firstItem() + $index }}</td>
                        <td class="px-4 py-3">{{ $reception->livestockReceptionH?->transaction_date ? date('d/m/Y', strtotime($reception->livestockReceptionH->transaction_date)) : '-' }}</td>
                        <td class="px-4 py-3 font-medium">{{ $reception->eartag_number }}</td>
                        <td class="px-4 py-3">{{ $reception->livestockType?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $reception->livestockBreed?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $reception->pen?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ number_format($reception->weight, 2, ',', '.') }} kg</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <x-button.action href="{{ route('admin.care-livestock.livestock-reception.show', [$farm->id, $reception->id]) }}" color="gray">Detail</x-button.action>
                                <x-button.action href="{{ route('admin.care-livestock.livestock-reception.edit', [$farm->id, $reception->id]) }}" color="blue">Edit</x-button.action>
                                <x-button.primary type="button" wire:click="delete({{ $reception->id }})" wire:confirm="Yakin ingin menghapus registrasi ini?" color="red" size="sm">Hapus</x-button.primary>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table.empty colspan="8" empty="Belum ada data registrasi ternak." />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $receptions->links() }}
    </div>
</div>
