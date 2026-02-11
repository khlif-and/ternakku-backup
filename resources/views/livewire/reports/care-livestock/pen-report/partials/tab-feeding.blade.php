<div x-show="activeTab === 'feeding'" x-cloak class="p-6">
    @php
        $feedingHeaders = [
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Pakan', 'class' => 'text-left'],
            ['label' => 'Jumlah', 'class' => 'text-left'],
        ];
    @endphp
    <x-table.wrapper :headers="$feedingHeaders">
        @forelse ($feedingHistory as $feeding)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($feeding->feedingH->transaction_date ?? now())->format('d M Y') }}</td>
                <td class="px-4 py-3 font-medium">{{ $feeding->feedingH->transaction_number ?? '-' }}</td>
                <td class="px-4 py-3">
                    @foreach ($feeding->feedingColonyItems ?? [] as $item)
                        <span class="block">{{ $item->feed->name ?? '-' }}</span>
                    @endforeach
                </td>
                <td class="px-4 py-3">
                    @foreach ($feeding->feedingColonyItems ?? [] as $item)
                        <span class="block">{{ $item->quantity ?? '-' }} {{ $item->feed->unit ?? '' }}</span>
                    @endforeach
                </td>
            </tr>
        @empty
            <x-table.empty colspan="4" empty="Tidak ada riwayat pemberian pakan pada periode ini." />
        @endforelse
    </x-table.wrapper>
</div>
