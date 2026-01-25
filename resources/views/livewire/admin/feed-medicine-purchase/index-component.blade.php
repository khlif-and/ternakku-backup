<div>
    <x-alert.session />

    <div class="mb-6 flex flex-wrap gap-4 items-end bg-gray-50 p-4 rounded-xl border border-gray-100">
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
            <input type="date" wire:model.live="start_date" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
            <input type="date" wire:model.live="end_date" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Pembelian</label>
            <select wire:model.live="purchase_type" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                @foreach($purchaseTypes as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="ml-auto">
            <x-button.action href="{{ route('admin.care-livestock.feed-medicine-purchase.create', $farm->id) }}" color="blue">
                + Tambah Pembelian
            </x-button.action>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        @php
            $headers = [
                ['label' => 'No', 'class' => 'text-left w-12'],
                ['label' => 'Tanggal', 'class' => 'text-left'],
                ['label' => 'Supplier', 'class' => 'text-left'],
                ['label' => 'Jumlah Item', 'class' => 'text-left'],
                ['label' => 'Total Pembelian', 'class' => 'text-left'],
                ['label' => 'Catatan', 'class' => 'text-left'],
                ['label' => 'Aksi', 'class' => 'text-right'],
            ];
        @endphp

        <x-table.wrapper :headers="$headers">
            @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ date('d/m/Y', strtotime($item->transaction_date)) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->supplier }}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs font-bold border border-blue-100">
                            {{ $item->feed_medicine_purchase_item_count }} Item
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-green-700">
                        Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 italic">
                        {{ Str::limit($item->notes ?: '-', 30) }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <x-button.action href="{{ route('admin.care-livestock.feed-medicine-purchase.show', [$farm->id, $item->id]) }}" color="blue" size="sm">
                                Detail
                            </x-button.action>
                            <x-button.action href="{{ route('admin.care-livestock.feed-medicine-purchase.edit', [$farm->id, $item->id]) }}" color="indigo" size="sm">
                                Edit
                            </x-button.action>
                            <x-button.primary wire:click="confirmDelete({{ $item->id }})" color="red" size="sm">
                                Hapus
                            </x-button.primary>
                        </div>
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="7" empty="Belum ada data pembelian yang sesuai." />
            @endforelse
        </x-table.wrapper>
    </div>
</div>