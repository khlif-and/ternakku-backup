<div> 
    <x-alert.session />

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Sisi Kiri: Transaction Summary & Status --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg border p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-800">Sales Details</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase tracking-wider">
                        #{{ $salesLivestock->id }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Transaction Date</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $salesLivestock->transaction_date ? date('d M Y', strtotime($salesLivestock->transaction_date)) : '-' }}
                        </div>
                    </div>

                    @if($salesLivestock->qurbanSalesOrder)
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Reference Order</div>
                        <a href="{{ route('admin.care-livestock.sales-order.edit', [$farm->id, $salesLivestock->qurban_sales_order_id]) }}" class="text-sm font-bold text-blue-600 hover:underline">
                            Sales Order #{{ $salesLivestock->qurban_sales_order_id }}
                        </a>
                        <div class="text-xs text-gray-400">
                            {{ date('d M Y', strtotime($salesLivestock->qurbanSalesOrder->order_date)) }}
                        </div>
                    </div>
                    @endif
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Amount</div>
                    <div class="text-xl font-bold text-green-700">
                        Rp {{ number_format($salesLivestock->qurbanSaleLivestockD->sum('price_per_head'), 0, ',', '.') }}
                    </div>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Total Items</div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-800">{{ $salesLivestock->qurbanSaleLivestockD->count() }} Head(s)</span>
                        <span class="text-xs font-medium text-gray-500">
                            Total W: {{ number_format($salesLivestock->qurbanSaleLivestockD->sum('min_weight'), 2) }} Kg
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Notes</div>
                    <div class="text-sm text-gray-800 italic">
                        {{ $salesLivestock->notes ?: '-' }}
                    </div>
                </div>

                <div class="pt-4 border-t space-y-2">
                    <x-button.action href="{{ route('admin.care-livestock.sales-livestock.edit', [$farm->id, $salesLivestock->id]) }}" color="blue" class="w-full justify-center">
                        Edit Sales
                    </x-button.action>
                    
                    <x-button.primary wire:click="delete" wire:confirm="Are you sure you want to delete this sales record? This will return the livestock to stock." color="red" class="w-full justify-center">
                        Delete Sales
                    </x-button.primary>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-lg border p-5 shadow-sm">
                <div class="mb-4 font-bold text-gray-800 border-b pb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Customer Profile
                </div>
                <div class="space-y-3">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Name</span>
                        <span class="text-sm font-bold text-gray-900">{{ $salesLivestock->qurbanCustomer->name }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Phone</span>
                        <span class="text-sm font-medium text-gray-800">{{ $salesLivestock->qurbanCustomer->phone ?: '-' }}</span>
                    </div>
                    <div class="flex flex-col border-t pt-2">
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Address</span>
                        <span class="text-sm font-medium text-gray-800">{{ $salesLivestock->qurbanCustomer->address ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Livestock Details --}}
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b bg-gray-50 font-bold text-gray-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Sold Livestock Items
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($salesLivestock->qurbanSaleLivestockD as $detail)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Kolom Kiri: Identitas Ternak --}}
                                    <div class="space-y-2">
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Livestock ID</div>
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                <span class="text-lg font-bold text-gray-800">
                                                    {{ $detail->livestock->identification_number }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-600 ml-4">
                                                {{ $detail->livestock->nickname ?? 'No Nickname' }}
                                            </div>
                                        </div>
                                        
                                        @if($detail->delivery_plan_date)
                                        <div class="inline-block px-3 py-1 bg-yellow-50 text-yellow-700 rounded-md text-xs font-semibold border border-yellow-100">
                                            Delivery: {{ date('d M Y', strtotime($detail->delivery_plan_date)) }}
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Kolom Kanan: Detail Harga & Berat --}}
                                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-md">
                                        <div>
                                            <div class="text-xs text-gray-400 font-bold uppercase">Weight</div>
                                            <div class="text-sm font-semibold text-gray-700">{{ number_format($detail->min_weight, 2) }} Kg</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-400 font-bold uppercase">Price / Kg</div>
                                            <div class="text-sm font-semibold text-gray-700">Rp {{ number_format($detail->price_per_kg, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="col-span-2 border-t pt-2 mt-1">
                                            <div class="text-xs text-gray-500 font-bold uppercase">Total Price</div>
                                            <div class="text-lg font-bold text-green-700">
                                                Rp {{ number_format($detail->price_per_head, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Summary Visual --}}
            <div class="bg-green-900 rounded-lg p-6 text-white shadow-md relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-1">
                        <h4 class="text-lg font-bold mb-2">Transaction Summary</h4>
                        <div class="grid grid-cols-2 gap-4 text-green-100">
                            <div>
                                <span class="block text-xs uppercase opacity-75">Total Head</span>
                                <span class="text-xl font-bold">{{ $salesLivestock->qurbanSaleLivestockD->count() }}</span>
                            </div>
                            <div>
                                <span class="block text-xs uppercase opacity-75">Grand Total</span>
                                <span class="text-xl font-bold">Rp {{ number_format($salesLivestock->qurbanSaleLivestockD->sum('price_per_head'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-green-200 mt-3 leading-relaxed">
                            *Ensure all delivery details are confirmed with the customer before dispatch.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-500 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                {{-- Background Decoration --}}
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-green-800 rounded-full opacity-50"></div>
            </div>
        </div>
    </div>
</div>