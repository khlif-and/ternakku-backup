<div x-show="activeTab === 'treatment'" x-cloak class="p-6">
    @php
        $treatmentHeaders = [
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Penyakit', 'class' => 'text-left'],
            ['label' => 'Obat', 'class' => 'text-left'],
        ];
    @endphp
    <x-table.wrapper :headers="$treatmentHeaders">
        @forelse ($treatmentHistory as $treatment)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($treatment->treatmentH->transaction_date ?? now())->format('d M Y') }}</td>
                <td class="px-4 py-3 font-medium">{{ $treatment->treatmentH->transaction_number ?? '-' }}</td>
                <td class="px-4 py-3">{{ $treatment->disease->name ?? '-' }}</td>
                <td class="px-4 py-3">
                    @foreach ($treatment->treatmentColonyMedicineItems ?? [] as $item)
                        <span class="block">{{ $item->medicine->name ?? '-' }} ({{ $item->dosage ?? '-' }})</span>
                    @endforeach
                </td>
            </tr>
        @empty
            <x-table.empty colspan="4" empty="Tidak ada riwayat pengobatan pada periode ini." />
        @endforelse
    </x-table.wrapper>
</div>
