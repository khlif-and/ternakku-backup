@section('title', 'Laporan Pengiriman Hewan Qurban')

<div>
    <x-admin.feature-card title="Laporan Pengiriman Hewan Qurban" :breadcrumbs="[
        ['label' => 'Qurban', 'route' => ''],
        ['label' => 'Laporan', 'route' => ''],
        ['label' => 'Pengiriman', 'route' => ''],
    ]">

        <div class="space-y-6">
            {{-- Filter --}}
            @include('livewire.reports.qurban.delivery-report.partials.filter')

            {{-- Statistics --}}
            @include('livewire.reports.qurban.delivery-report.partials.statistics')

            {{-- Table --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="font-semibold text-gray-700">Daftar Pengiriman</h3>

                    @if ($data->isNotEmpty())
                        <a href="{{ route('qurban.report.delivery.export-detailed', request()->query()) }}" target="_blank"
                            class="btn-sm btn-primary flex items-center gap-2">
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
                                                    {{-- $item is an ARRAY (Resolved Resource) --}}
                                                    <tr class="hover:bg-gray-50">
                                                        {{-- delivery_date --}}
                                                        <td class="px-6 py-4">
                                                            {{ \Carbon\Carbon::parse($item['delivery_date'])->format('d M Y') }}
                                                        </td>

                                                        {{-- transaction_number --}}
                                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item['transaction_number'] }}</td>

                                                        {{-- driver name (nested array from UserResource) --}}
                                                        <td class="px-6 py-4">{{ $item['driver']['name'] ?? '-' }}</td>

                                                        {{-- fleet police_number (nested array from FleetResource) --}}
                                                        <td class="px-6 py-4">{{ $item['fleet']['police_number'] ?? '-' }}</td>

                                                        {{-- delivery_orders (nested array/collection) --}}
                                                        <td class="px-6 py-4">{{ count($item['delivery_orders'] ?? []) }}</td>

                                                        <td class="px-6 py-4">
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                                                                                    {{ $item['status'] == 'delivered'
                                ? 'bg-green-100 text-green-800'
                                : ($item['status'] == 'in_delivery'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-yellow-100 text-yellow-800') }}">
                                                                {{ ucfirst(str_replace('_', ' ', $item['status'])) }}
                                                            </span>
                                                        </td>
                                                    </tr>
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

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </x-admin.feature-card>
</div>