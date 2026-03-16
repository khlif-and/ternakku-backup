@section('title', 'Laporan Detail Pengiriman Hewan Qurban')

<div>
    <x-admin.feature-card title="Laporan Detail Pengiriman Hewan Qurban" :breadcrumbs="[
        ['label' => 'Qurban', 'route' => ''],
        ['label' => 'Laporan', 'route' => ''],
        ['label' => 'Detail Pengiriman', 'route' => ''],
    ]">
        <div class="space-y-6">
            @include('livewire.reports.qurban.detailed-delivery-report.partials.filter')

            @if ($showReport)
                @include('livewire.reports.qurban.detailed-delivery-report.partials.statistics')

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="font-semibold text-gray-700">Daftar Detail Pengiriman</h3>

                        @if ($data->isNotEmpty())
                                        <a href="{{ route('qurban.report.delivery.export-detailed', [
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'driver_id' => $driver_id,
                                'fleet_id' => $fleet_id,
                                'status' => $status,
                            ]) }}" target="_blank" class="btn-sm btn-primary flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Export PDF
                                        </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">No Transaksi</th>
                                    <th class="px-6 py-3">Driver</th>
                                    <th class="px-6 py-3">Armada</th>
                                    <th class="px-6 py-3">Total Pesanan</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($data as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($item['delivery_date'])->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $item['transaction_number'] }}
                                        </td>
                                        <td class="px-6 py-4">{{ $item['driver']['name'] ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $item['fleet']['police_number'] ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ count($item['delivery_orders'] ?? []) }} Pesanan
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusVal = $item['status'];
                                                $statusClass = match ($statusVal) {
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'in_delivery' => 'bg-blue-100 text-blue-800',
                                                    'ready_to_deliver' => 'bg-yellow-100 text-yellow-800',
                                                    'scheduled' => 'bg-purple-100 text-purple-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $statusVal)) }}
                                            </span>
                                        </td>
                                    </tr>

                                    @if(count($item['delivery_orders'] ?? []) > 0)
                                        <tr class="bg-gray-50/50">
                                            <td colspan="6" class="px-6 py-3">
                                                <div class="border rounded-lg overflow-hidden bg-white">
                                                    <table class="w-full text-xs">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-gray-600 font-semibold">No.
                                                                    Surat Jalan</th>
                                                                <th class="px-3 py-2 text-left text-gray-600 font-semibold">Penerima
                                                                </th>
                                                                <th class="px-3 py-2 text-left text-gray-600 font-semibold">No. HP
                                                                </th>
                                                                <th class="px-3 py-2 text-left text-gray-600 font-semibold">Alamat
                                                                </th>
                                                                <th class="px-3 py-2 text-center text-gray-600 font-semibold">Jumlah
                                                                    Ternak</th>
                                                                <th class="px-3 py-2 text-center text-gray-600 font-semibold">Status
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-100">
                                                            @foreach($item['delivery_orders'] as $order)
                                                                                                    <tr class="hover:bg-gray-50">
                                                                                                        <td class="px-3 py-2 font-medium text-gray-800">
                                                                                                            {{ $order['transaction_number'] }}
                                                                                                        </td>
                                                                                                        <td class="px-3 py-2 font-semibold text-gray-900">
                                                                                                            {{ $order['recipient_name'] }}
                                                                                                        </td>
                                                                                                        <td class="px-3 py-2 text-gray-600">
                                                                                                            {{ $order['recipient_phone'] }}
                                                                                                        </td>
                                                                                                        <td class="px-3 py-2 text-gray-600 max-w-xs truncate"
                                                                                                            title="{{ $order['recipient_address'] }}">
                                                                                                            {{ \Illuminate\Support\Str::limit($order['recipient_address'], 50) }}
                                                                                                        </td>
                                                                                                        <td class="px-3 py-2 text-center font-bold text-gray-800">
                                                                                                            {{ $order['livestock_count'] }} Ekor
                                                                                                        </td>
                                                                                                        <td class="px-3 py-2 text-center">
                                                                                                            @php
                                                                                                                $orderStatus = $order['status'] ?? '-';
                                                                                                                $orderStatusClass = match ($orderStatus) {
                                                                                                                    'delivered' => 'bg-green-100 text-green-800',
                                                                                                                    'in_delivery' => 'bg-blue-100 text-blue-800',
                                                                                                                    'ready_to_deliver' => 'bg-yellow-100 text-yellow-800',
                                                                                                                    default => 'bg-gray-100 text-gray-800',
                                                                                                                };
                                                                                                            @endphp
                                                                 <span
                                                                                                                class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $orderStatusClass }}">
                                                                                                                {{ ucfirst(str_replace('_', ' ', $orderStatus)) }}
                                                                                                            </span>
                                                                                                        </td>
                                                                                                    </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <p>Tidak ada data pengiriman ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $data->links() }}
                    </div>
                </div>
            @endif
        </div>
    </x-admin.feature-card>
</div>