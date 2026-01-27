<div>
    <x-alert.session />

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Sisi Kiri: Customer Profile & Actions --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Main Profile Card --}}
            <div class="bg-white rounded-lg border p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-800">Customer Profile</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase tracking-wider">
                        #{{ $customer->id }}
                    </span>
                </div>

                <div class="text-center py-4">
                    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center text-3xl mb-3">
                        🐱 </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $customer->name }}</h2>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mt-1">
                        Joined {{ $customer->created_at->format('d M Y') }}
                    </div>
                </div>

                <div class="space-y-3 pt-2 border-t">
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Status</div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-sm font-semibold text-gray-700">Active</span>
                        </div>
                    </div>

                    @if($customer->user)
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Linked Account</div>
                        <div class="flex items-center gap-2 bg-gray-50 p-2 rounded border">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ $customer->user->name }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="pt-4 border-t space-y-2">
                    <x-button.action href="{{ route('admin.care-livestock.customer.edit', [$farm->id, $customer->id]) }}" color="blue" class="w-full justify-center">
                        Edit Customer
                    </x-button.action>
                    
                    <x-button.primary wire:click="delete" wire:confirm="Are you sure you want to delete this customer? This will remove all associated addresses and history." color="red" class="w-full justify-center">
                        Delete Customer
                    </x-button.primary>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="bg-white rounded-lg border p-5 shadow-sm">
                <div class="mb-4 font-bold text-gray-800 border-b pb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact Details
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 bg-green-50 p-1.5 rounded text-green-600">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Phone Number</div>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $customer->phone ?: '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-1 bg-blue-50 p-1.5 rounded text-blue-600">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Email Address</div>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $customer->email ?: '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Address List & Insights --}}
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b bg-gray-50 font-bold text-gray-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Registered Addresses
                    </div>
                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">{{ $customer->addresses->count() }} Locations</span>
                </div>

                <div class="p-6">
                    @forelse($customer->addresses as $address)
                        <div class="relative pl-8 pb-6 border-l-2 border-gray-200 last:pb-0 last:border-l-0">
                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-2 border-red-500"></div>
                            
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:border-blue-200 transition">
                                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-2 mb-2">
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-base flex items-center gap-2">
                                            {{ $address->name }}
                                            @if($address->region)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-800">
                                                    {{ $address->region->name }}
                                                </span>
                                            @endif
                                        </h4>
                                    </div>
                                    @if($address->postal_code)
                                        <span class="text-xs font-mono bg-white border px-2 py-1 rounded text-gray-500">
                                            ZIP: {{ $address->postal_code }}
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-sm text-gray-600 leading-relaxed mb-3">
                                    {{ $address->address_line }}
                                </p>

                                @if($address->latitude && $address->longitude)
                                    <div class="flex items-center gap-2 text-xs text-blue-600 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7m0 0L9 7" />
                                        </svg>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $address->latitude }},{{ $address->longitude }}" target="_blank" class="hover:underline">
                                            {{ $address->latitude }}, {{ $address->longitude }}
                                        </a>
                                    </div>
                                @endif

                                @if($address->description)
                                    <div class="text-xs text-gray-500 italic border-t pt-2 mt-2">
                                        Note: {{ $address->description }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p>No addresses registered yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Customer Insight / Next Steps Visual --}}
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-lg p-6 text-white shadow-md relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-1">
                        <h4 class="text-lg font-bold mb-2">Customer Engagement</h4>
                        <p class="text-sm text-blue-100 leading-relaxed">
                            Ensure customer data is up-to-date for accurate delivery and communication. 
                            Use the address list to plan logistics for Qurban or Livestock delivery efficiently.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-blue-400 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                {{-- Background Decoration --}}
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-700 rounded-full opacity-30"></div>
            </div>

        </div>
    </div>
</div>