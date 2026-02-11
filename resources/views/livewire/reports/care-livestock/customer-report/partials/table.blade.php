<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Pelanggan</h3>
        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">
            Total: {{ $customers->total() }} Data
        </span>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Pelanggan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No. Telepon / Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jumlah Pesanan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal Bergabung
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $index => $item)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $customers->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                        <span class="text-emerald-600 font-bold text-lg">
                                            {{ substr($item->user->name ?? 'C', 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->user->name ?? 'Tanpa Nama' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $item->user->roles->first()->name ?? 'Customer' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->user->phone_number ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $item->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $item->sales_orders_count ?? $item->salesOrders->count() }} Pesanan
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-base font-medium text-gray-900">Tidak ada pelanggan ditemukan</p>
                                <p class="text-sm text-gray-500 mt-1">Coba ubah filter pencarian Anda atau tambahkan pelanggan baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($customers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $customers->links() }}
        </div>
    @endif
</div>
