@props(['items'])

<div class="bg-white rounded-lg border overflow-hidden shadow-sm">
    <div class="px-5 py-4 border-b bg-gray-50 font-bold text-gray-700 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        Daftar Hewan Pesanan
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 tracking-wider">
                    <th class="px-6 py-3 font-semibold">Jenis Hewan</th>
                    <th class="px-6 py-3 font-semibold text-center">Jumlah (Ekor)</th>
                    <th class="px-6 py-3 font-semibold text-center">Total Berat (Kg)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $detail)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $detail->livestockType->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-700">
                            {{ $detail->quantity }}
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-700">
                            {{ $detail->total_weight }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada detail hewan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($items->isNotEmpty())
                <tfoot class="bg-gray-50 border-t border-gray-100">
                    <tr>
                        <td class="px-6 py-3 text-sm font-bold text-gray-900 text-right">Total</td>
                        <td class="px-6 py-3 text-sm font-bold text-gray-900 text-center">
                            {{ $items->sum('quantity') }}
                        </td>
                        <td class="px-6 py-3 text-sm font-bold text-gray-900 text-center">
                            {{ $items->sum('total_weight') }}
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
