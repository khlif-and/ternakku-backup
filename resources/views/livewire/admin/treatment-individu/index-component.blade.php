<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
            <select wire:model.live="pen_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Kandang</option>
                @foreach($pens as $pen)
                    <option value="{{ $pen->id }}">{{ $pen->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="disease_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Penyakit</option>
                @foreach($diseases as $disease)
                    <option value="{{ $disease->id }}">{{ $disease->name }}</option>
                @endforeach
            </select>
        </div>
        <x-button.link href="{{ route('admin.care-livestock.treatment-individu.create', $farm->id) }}" color="green">
            + Tambah Treatment Individu
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'Ternak', 'class' => 'text-left'],
            ['label' => 'Kandang', 'class' => 'text-left'],
            ['label' => 'Penyakit', 'class' => 'text-left'],
            ['label' => 'Total Biaya', 'class' => 'text-left'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($items as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b">{{ $index + 1 }}</td>
                <td class="px-4 py-3 border-b">{{ $item->treatmentH?->transaction_date ? date('d/m/Y', strtotime($item->treatmentH->transaction_date)) : '-' }}</td>
                <td class="px-4 py-3 border-b">
                    <div class="font-medium text-gray-900">{{ $item->livestock?->eartag_number }}</div>
                    <div class="text-xs text-gray-500">{{ $item->livestock?->name }}</div>
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-600">{{ $item->livestock?->pen?->name ?? '-' }}</td>
                <td class="px-4 py-3 border-b">
                    <span class="px-2 py-1 bg-red-50 text-red-700 rounded text-xs font-semibold border border-red-100">
                        {{ $item->disease?->name ?? 'Tidak Diketahui' }}
                    </span>
                </td>
                <td class="px-4 py-3 border-b font-semibold">Rp {{ number_format($item->total_cost, 0, ',', '.') }}</td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.care-livestock.treatment-individu.show', [$farm->id, $item->id]) }}" color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('admin.care-livestock.treatment-individu.edit', [$farm->id, $item->id]) }}" color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus data treatment ini?" color="red" size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="7" empty="Belum ada data treatment individu." />
        @endforelse
    </x-table.wrapper>
</div>