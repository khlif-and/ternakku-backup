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
        </div>
        <x-button.link href="{{ route('admin.care-livestock.livestock-death.create', $farm->id) }}" color="green">
            + Tambah Kematian
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
                                <x-button.action href="{{ route('admin.care-livestock.livestock-death.show', [$farm->id, $death->id]) }}" color="gray">Detail</x-button.action>
                                <x-button.action href="{{ route('admin.care-livestock.livestock-death.edit', [$farm->id, $death->id]) }}" color="blue">Edit</x-button.action>
                                <x-button.primary type="button" wire:click="delete({{ $death->id }})" wire:confirm="Yakin ingin menghapus data kematian ini?" color="red" size="sm">Hapus</x-button.primary>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-table.empty colspan="7" empty="Belum ada data kematian ternak." />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $deaths->links() }}
    </div>
</div>
