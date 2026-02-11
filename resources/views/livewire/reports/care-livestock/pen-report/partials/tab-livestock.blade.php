<div x-show="activeTab === 'livestock'" class="p-6">
    @php
        $livestockHeaders = [
            ['label' => 'No', 'class' => 'text-left'],
            ['label' => 'Kode/Eartag', 'class' => 'text-left'],
            ['label' => 'Jenis', 'class' => 'text-left'],
            ['label' => 'Jenis Kelamin', 'class' => 'text-left'],
            ['label' => 'Klasifikasi', 'class' => 'text-left'],
            ['label' => 'Status', 'class' => 'text-left'],
        ];
    @endphp
    <x-table.wrapper :headers="$livestockHeaders">
        @forelse ($livestocks as $index => $livestock)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ $index + 1 }}</td>
                <td class="px-4 py-3 font-medium">{{ $livestock->eartag ?? $livestock->code ?? '-' }}</td>
                <td class="px-4 py-3">{{ $livestock->livestockType->name ?? '-' }}</td>
                <td class="px-4 py-3">{{ $livestock->livestockSex->name ?? '-' }}</td>
                <td class="px-4 py-3">{{ $livestock->livestockClassification->name ?? '-' }}</td>
                <td class="px-4 py-3">
                    @if ($livestock->is_alive ?? true)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hidup</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Mati</span>
                    @endif
                </td>
            </tr>
        @empty
            <x-table.empty colspan="6" empty="Tidak ada data ternak di kandang ini." />
        @endforelse
    </x-table.wrapper>
</div>
