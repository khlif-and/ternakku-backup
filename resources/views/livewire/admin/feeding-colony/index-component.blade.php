<div>
    <x-alert.session />

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

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'Kandang', 'class' => 'text-left'],
            ['label' => 'Jumlah Ternak', 'class' => 'text-left'],
            ['label' => 'Total Biaya', 'class' => 'text-left'],
            ['label' => 'Rata-rata/Ekor', 'class' => 'text-left'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($items as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b">{{ $index + 1 }}</td>
                <td class="px-4 py-3 border-b">{{ $item->feedingH?->transaction_date ? date('d/m/Y', strtotime($item->feedingH->transaction_date)) : '-' }}</td>
                <td class="px-4 py-3 border-b font-medium text-gray-900">{{ $item->pen?->name }}</td>
                <td class="px-4 py-3 border-b">{{ $item->total_livestock }} ekor</td>
                <td class="px-4 py-3 border-b">Rp {{ number_format($item->total_cost, 0, ',', '.') }}</td>
                <td class="px-4 py-3 border-b">Rp {{ number_format($item->average_cost, 0, ',', '.') }}</td>
                <td class="px-4 py-3 border-b">
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
    </x-table.wrapper>
</div>
