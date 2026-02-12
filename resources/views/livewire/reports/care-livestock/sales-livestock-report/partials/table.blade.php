<div class="overflow-x-auto">
    <table class="table w-full">
        <thead>
            <tr>
                <th>No Transaksi</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th class="text-center">Jumlah Ternak</th>
                <th class="text-right">Total Nominal</th>
                <th class="text-center">Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                @php
                    $saleTotal = 0;
                    foreach ($sale->qurbanSaleLivestockD as $detail) {
                        $saleTotal += ($detail->price_per_head ?? 0) + (($detail->price_per_kg ?? 0) * ($detail->weight ?? 0));
                    }
                @endphp
                <tr>
                    <td class="font-medium">{{ $sale->transaction_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->transaction_date)->format('d/m/Y') }}</td>
                    <td>
                        <div class="flex flex-col">
                            <span class="font-bold">{{ $sale->qurbanCustomer->name ?? '-' }}</span>
                            <span class="text-xs text-gray-500">{{ $sale->qurbanCustomer->phone ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="text-center">{{ $sale->qurbanSaleLivestockD->count() }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($saleTotal, 0, ',', '.') }}</td>
                    <td class="text-center">
                        {{-- Maybe add a modal or expand row for details later --}}
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-xs m-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path
                                        d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                </svg>
                            </label>
                            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 z-[1]">
                                <li>
                                    <h3 class="font-bold px-2">Item Detail</h3>
                                </li>
                                @foreach($sale->qurbanSaleLivestockD as $detail)
                                    <li>
                                        <div class="flex flex-col items-start gap-0 py-1">
                                            <span class="text-xs font-bold">{{ $detail->livestock->eartag ?? 'Unknown' }}</span>
                                            <span class="text-[10px] text-gray-500">{{ $detail->weight ?? 0 }} Kg</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">
                        Tidak ada data penjualan ditemukan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>