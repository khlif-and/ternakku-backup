<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
            
            <select wire:model.live="livestock_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Ternak</option>
                @foreach($livestocks as $livestock)
                    <option value="{{ $livestock->id }}">{{ $livestock->identification_number }} - {{ $livestock->nickname ?? 'Tanpa Nama' }}</option>
                @endforeach
            </select>

            <select wire:model.live="pen_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Kandang</option>
                @foreach($pens as $pen)
                    <option value="{{ $pen->id }}">{{ $pen->name }}</option>
                @endforeach
            </select>
        </div>
        
        <x-button.link href="{{ route('admin.care-livestock.mutation-individu.create', $farm->id) }}" color="green">
            + Catat Mutasi Baru
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'Ternak', 'class' => 'text-left'],
            ['label' => 'Dari Kandang', 'class' => 'text-left'],
            ['label' => 'Kandang Tujuan', 'class' => 'text-left'],
            ['label' => 'Catatan', 'class' => 'text-left'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($items as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b">{{ $index + 1 }}</td>
                <td class="px-4 py-3 border-b">
                    {{ $item->mutationH?->transaction_date ? date('d/m/Y', strtotime($item->mutationH->transaction_date)) : '-' }}
                </td>
                <td class="px-4 py-3 border-b font-medium text-gray-900">
                    <div class="text-sm font-bold">{{ $item->livestock?->identification_number }}</div>
                    <div class="text-xs text-gray-500">{{ $item->livestock?->nickname }}</div>
                </td>
                <td class="px-4 py-3 border-b">
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs border">
                        {{ $item->fromPen?->name ?? 'Kandang ' . $item->from }}
                    </span>
                </td>
                <td class="px-4 py-3 border-b">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-semibold border border-blue-100">
                        {{ $item->toPen?->name ?? 'Kandang ' . $item->to }}
                    </span>
                </td>
                <td class="px-4 py-3 border-b text-gray-600 text-sm italic">
                    {{ Str::limit($item->notes ?: '-', 30) }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.care-livestock.mutation-individu.show', [$farm->id, $item->id]) }}" color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('admin.care-livestock.mutation-individu.edit', [$farm->id, $item->id]) }}" color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus data mutasi ini? Posisi ternak akan dikembalikan ke kandang asal." color="red" size="sm">
                            Hapus
                        </x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="7" empty="Belum ada data mutasi individu." />
        @endforelse
    </x-table.wrapper>
</div>