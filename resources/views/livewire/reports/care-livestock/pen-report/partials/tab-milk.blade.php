<div x-show="activeTab === 'milk'" x-cloak class="p-6">
    @if (count($milkProduction) > 0)
        <div class="mb-4 p-4 bg-blue-50 rounded-lg">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-blue-600">Total Produksi:</span>
                    <span class="font-bold text-blue-800">{{ number_format($statistics['total_milk'] ?? 0, 2) }} Liter</span>
                </div>
                <div>
                    <span class="text-blue-600">Rata-rata per Record:</span>
                    <span class="font-bold text-blue-800">{{ number_format($statistics['avg_milk_per_day'] ?? 0, 2) }} Liter</span>
                </div>
            </div>
        </div>
    @endif
    
    @php
        $milkHeaders = [
            ['label' => 'Tanggal', 'class' => 'text-left'],
            ['label' => 'No. Transaksi', 'class' => 'text-left'],
            ['label' => 'Volume (L)', 'class' => 'text-left'],
        ];
    @endphp
    <x-table.wrapper :headers="$milkHeaders">
        @forelse ($milkProduction as $production)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($production->milkProductionH->transaction_date ?? now())->format('d M Y') }}</td>
                <td class="px-4 py-3 font-medium">{{ $production->milkProductionH->transaction_number ?? '-' }}</td>
                <td class="px-4 py-3 font-medium">{{ number_format($production->volume ?? 0, 2) }}</td>
            </tr>
        @empty
            <x-table.empty colspan="3" empty="Tidak ada data produksi susu pada periode ini." />
        @endforelse
    </x-table.wrapper>
</div>
