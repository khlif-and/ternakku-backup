<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="w-full md:w-48">
                <select wire:model.live="livestock_id" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 shadow-sm outline-none">
                    <option value="">Semua Ternak</option>
                    @foreach($livestocks as $livestock)
                        <option value="{{ $livestock->id }}">{{ $livestock->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 shadow-sm outline-none">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 shadow-sm outline-none">
        </div>

        <x-button.link href="{{ route('admin.care-livestock.milk-analysis-individu.create', $farm->id) }}" color="green">
            <span class="font-bold">+ Tambah Analisis Individu</span>
        </x-button.link>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        @php
            $headers = [
                ['label' => 'No', 'class' => 'text-left w-12'],
                ['label' => 'Tanggal', 'class' => 'text-left'],
                ['label' => 'Ternak', 'class' => 'text-left'],
                ['label' => 'BJ', 'class' => 'text-center'],
                ['label' => 'Lemak (%)', 'class' => 'text-center'],
                ['label' => 'Protein (%)', 'class' => 'text-center'],
                ['label' => 'Status', 'class' => 'text-center'],
                ['label' => 'Aksi', 'class' => 'text-center'],
            ];
        @endphp

        <x-table.wrapper :headers="$headers">
            @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-500 border-b">
                        {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900 border-b">
                        {{ date('d/m/Y', strtotime($item->milkAnalysisH->transaction_date)) }}
                    </td>
                    <td class="px-4 py-3 text-sm border-b">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-900">{{ $item->livestock->full_name }}</span>
                            <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ $item->livestock->eartag_code }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-mono text-blue-600 border-b">
                        {{ number_format($item->bj, 4, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-bold text-gray-700 border-b">
                        {{ number_format($item->fat, 2, ',', '.') }}%
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-bold text-gray-700 border-b">
                        {{ number_format($item->protein, 2, ',', '.') }}%
                    </td>
                    <td class="px-4 py-3 text-sm text-center border-b">
                        <div class="flex flex-col gap-1 items-center">
                            <span class="px-2 py-0.5 {{ $item->at ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} rounded text-[9px] font-black uppercase">
                                AT: {{ $item->at ? 'POS' : 'NEG' }}
                            </span>
                            <span class="px-2 py-0.5 {{ $item->ab ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} rounded text-[9px] font-black uppercase">
                                AB: {{ $item->ab ? 'POS' : 'NEG' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-center border-b">
                        <div class="flex items-center justify-center gap-2">
                            <x-button.action href="{{ route('admin.care-livestock.milk-analysis-individu.show', [$farm->id, $item->id]) }}" color="gray">
                                Detail
                            </x-button.action>
                            
                            <x-button.action href="{{ route('admin.care-livestock.milk-analysis-individu.edit', [$farm->id, $item->id]) }}" color="blue">
                                Edit
                            </x-button.action>
                            
                            <x-button.primary type="button" 
                                wire:click="delete({{ $item->id }})" 
                                wire:confirm="Hapus data analisis ternak {{ $item->livestock->full_name }}?" 
                                color="red" size="sm">
                                Hapus
                            </x-button.primary>
                        </div>
                    </td>
                </tr>
            @empty
                <x-table.empty colspan="8" empty="Data analisis susu individu belum tersedia." />
            @endforelse
        </x-table.wrapper>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>