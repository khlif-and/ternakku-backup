<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Penimbangan Ulang</h2>
        <x-button.primary href="{{ route('admin.care-livestock.reweight.create', $farm->id) }}">
            Tambah Penimbangan
        </x-button.primary>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-form.input type="date" label="Tanggal Awal" wire:model.live="start_date" />
            <x-form.input type="date" label="Tanggal Akhir" wire:model.live="end_date" />
            <x-form.input type="text" label="Cari Eartag" wire:model.live.debounce.500ms="search" placeholder="Cari..." />
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ternak</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Berat (Kg)</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reweights as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ date('d/m/Y', strtotime($item->livestockReweightH->transaction_date)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->livestockReweightH->transaction_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-bold">{{ $item->livestock->eartag ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $item->livestock->livestockType->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                {{ $item->weight }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-xs">
                                {{ $item->livestockReweightH->notes ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <x-button.action href="{{ route('admin.care-livestock.reweight.show', [$farm->id, $item->id]) }}" color="green">
                                        Detail
                                    </x-button.action>
                                    <x-button.action href="{{ route('admin.care-livestock.reweight.edit', [$farm->id, $item->id]) }}" color="blue">
                                        Edit
                                    </x-button.action>
                                    <button wire:click="delete({{ $item->id }})" wire:confirm="Apakah Anda yakin ingin menghapus data ini?" class="text-red-600 hover:text-red-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data penimbangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $reweights->links() }}
        </div>
    </div>
</div>
