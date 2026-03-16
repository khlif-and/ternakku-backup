<div> 
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
             <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Search customer..." class="pl-10 px-4 py-2 border rounded-lg text-sm focus:ring-blue-500 w-full md:w-64">
            </div>
        </div>
        <x-button.link href="{{ route('admin.care-livestock.customer.create', $farm->id) }}" color="green">
            + Add Customer
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Name', 'class' => 'text-left'],
            ['label' => 'Contact Info', 'class' => 'text-left'],
            ['label' => 'Registered Addresses', 'class' => 'text-center'],
            ['label' => 'Joined At', 'class' => 'text-left'],
            ['label' => 'Action', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($customers as $index => $customer)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $index + 1 }}</td>
                <td class="px-4 py-3 border-b">
                    <div class="text-sm font-bold text-gray-900">{{ $customer->user->name ?? '-' }}</div>
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex flex-col gap-1">
                        @if($customer->user->phone_number ?? false)
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <span class="w-4 h-4 flex items-center justify-center bg-gray-100 rounded">📞</span>
                                {{ $customer->user->phone_number }}
                            </div>
                        @endif
                        @if($customer->user->email ?? false)
                            <div class="flex items-center gap-2 text-xs text-blue-600">
                                <span class="w-4 h-4 flex items-center justify-center bg-blue-50 rounded">✉️</span>
                                {{ $customer->user->email }}
                            </div>
                        @endif
                        @if(!($customer->user->phone_number ?? false) && !($customer->user->email ?? false))
                            <span class="text-xs text-gray-400 italic">No contact info</span>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3 border-b text-center">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold border border-blue-100">
                        {{ $customer->addresses->count() }} Locations
                    </span>
                </td>
                <td class="px-4 py-3 border-b text-sm text-gray-700">
                    {{ $customer->created_at->format('d/m/Y') }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.care-livestock.customer.show', [$farm->id, $customer->id]) }}" color="gray">Detail</x-button.action>
                        
                        <x-button.action href="{{ route('admin.care-livestock.customer.address.index', [$farm->id, $customer->id]) }}" color="yellow" title="Manage Addresses">
                            Address
                        </x-button.action>

                        <x-button.action href="{{ route('admin.care-livestock.customer.edit', [$farm->id, $customer->id]) }}" color="blue">Edit</x-button.action>
                        
                        <x-button.primary type="button" wire:click="delete({{ $customer->id }})" wire:confirm="Are you sure you want to delete this customer? All associated addresses will also be deleted." color="red" size="sm">Delete</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="6" empty="No customer data found." />
        @endforelse
    </x-table.wrapper>
</div>