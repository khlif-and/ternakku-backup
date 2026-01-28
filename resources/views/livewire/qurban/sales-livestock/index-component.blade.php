<div> 
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
            
            <select wire:model.live="qurban_customer_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">All Customers</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <x-button.link href="{{ route('admin.care-livestock.sales-livestock.create', $farm->id) }}" color="green">
            + Add Sales
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Date', 'class' => 'text-left'],
            ['label' => 'Customer', 'class' => 'text-left'],
            ['label' => 'Sales Order', 'class' => 'text-left'],
            ['label' => 'Total Head', 'class' => 'text-center'],
            ['label' => 'Total Weight', 'class' => 'text-center'],
            ['label' => 'Action', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($sales as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $sales->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->transaction_date ? date('d/m/Y', strtotime($item->transaction_date)) : '-' }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="text-sm font-bold text-gray-900">{{ $item->qurbanCustomer?->name ?? '-' }}</div>
                    <div class="text-xs text-gray-500">{{ $item->qurbanCustomer?->phone ?? '' }}</div>
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    @if($item->qurbanSalesOrder)
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-semibold border border-blue-100">
                            #{{ $item->qurbanSalesOrder->id }} - {{ date('d/m/y', strtotime($item->qurbanSalesOrder->order_date)) }}
                        </span>
                    @else
                        <span class="text-gray-400 text-xs italic">Direct Sales</span>
                    @endif
                </td>
                <td class="px-4 py-3 border-b text-center text-sm font-semibold">
                    {{ $item->qurbanSaleLivestockD->count() }}
                </td>
                <td class="px-4 py-3 border-b text-center text-sm font-semibold text-gray-800">
                    {{ number_format($item->qurbanSaleLivestockD->sum('min_weight'), 2) }} Kg
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.care-livestock.sales-livestock.show', [$farm->id, $item->id]) }}" color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('admin.care-livestock.sales-livestock.edit', [$farm->id, $item->id]) }}" color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Are you sure you want to delete this sales record?" color="red" size="sm">Delete</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="7" empty="No sales data found." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>