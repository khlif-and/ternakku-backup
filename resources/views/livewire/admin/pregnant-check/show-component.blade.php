<div> <x-alert.session />

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Sisi Kiri: Transaction Summary & Officer --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-lg border p-5 space-y-4 shadow-sm">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold text-gray-800">Check Details</h3>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase tracking-wider">
                    {{ $item->pregnantCheck->transaction_number ?? 'NO REF' }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Date</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $item->pregnantCheck->transaction_date ? date('d M Y', strtotime($item->pregnantCheck->transaction_date)) : '-' }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Time</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $item->action_time ? date('H:i', strtotime($item->action_time)) : '-' }}
                    </div>
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Officer Name</div>
                <div class="text-sm font-semibold text-gray-800">{{ $item->officer_name }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Check Cost</div>
                <div class="text-lg font-bold text-blue-700">
                    Rp {{ number_format($item->cost ?? 0, 0, ',', '.') }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Notes</div>
                <div class="text-sm text-gray-800 italic">
                    {{ $item->pregnantCheck->notes ?: '-' }}
                </div>
            </div>

            <div class="pt-4 border-t space-y-2">
                <x-button.action href="{{ route('admin.care_livestock.pregnant_check.edit', ['farm_id' => $farm->id, 'id' => $item->id]) }}" color="blue" class="w-full justify-center">
                    Edit Record
                </x-button.action>
                
                <x-button.primary wire:click="delete" wire:confirm="Are you sure? This will delete the record and revert reproduction status." color="red" class="w-full justify-center">
                    Delete Record
                </x-button.primary>
            </div>
        </div>

        {{-- Female Info --}}
        <div class="bg-white rounded-lg border p-5 shadow-sm">
            <div class="mb-4 font-bold text-gray-800 border-b pb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                Female Profile
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">ID / Eartag</span>
                    <span class="text-sm font-bold text-gray-900">{{ $item->reproductionCycle->livestock->identification_number }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Nickname</span>
                    <span class="text-sm font-medium text-gray-800">{{ $item->reproductionCycle->livestock->nickname ?: '-' }}</span>
                </div>
                <div class="flex items-center justify-between border-t pt-2">
                    <span class="text-xs text-gray-500">Current Pen</span>
                    <span class="text-sm font-medium text-gray-800">{{ $item->reproductionCycle->livestock->pen->name ?: '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Examination Results --}}
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b bg-gray-50 font-bold text-gray-700 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Examination Results
            </div>

            <div class="p-6">
                {{-- Status Banner --}}
                <div class="mb-8 text-center">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">Pregnancy Status</label>
                    @if($item->status === 'PREGNANT')
                        <div class="inline-flex items-center justify-center px-6 py-2 rounded-full bg-green-100 text-green-700 border border-green-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xl font-bold tracking-wide">POSITIVE (PREGNANT)</span>
                        </div>
                    @elseif($item->status === 'NOT_PREGNANT')
                        <div class="inline-flex items-center justify-center px-6 py-2 rounded-full bg-red-100 text-red-700 border border-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xl font-bold tracking-wide">NEGATIVE (NOT PREGNANT)</span>
                        </div>
                    @else
                        <div class="inline-flex items-center justify-center px-6 py-2 rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-xl font-bold tracking-wide">INCONCLUSIVE</span>
                        </div>
                    @endif
                </div>

                @if($item->status === 'PREGNANT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Pregnant Age</label>
                                <div class="flex items-center gap-2 text-3xl font-bold text-gray-800">
                                    {{ $item->pregnant_age }}
                                    <span class="text-base font-normal text-gray-500 self-end mb-1">Months</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Est. Birth Date</label>
                                <div class="text-xl font-bold text-indigo-700">
                                    {{ $item->estimated_birth_date ? date('d F Y', strtotime($item->estimated_birth_date)) : '-' }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">Calculated based on livestock type gestation period.</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 bg-red-50 rounded-lg border border-red-100 text-center">
                        <p class="text-red-700 font-medium">
                            The livestock is not pregnant. Please evaluate the reproduction cycle and prepare for the next estrus cycle or re-insemination.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Next Steps Visual --}}
        <div class="{{ $item->status === 'PREGNANT' ? 'bg-green-900' : 'bg-gray-800' }} rounded-lg p-6 text-white shadow-md relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                <div class="flex-1">
                    <h4 class="text-lg font-bold mb-2">Next Steps?</h4>
                    <p class="text-sm text-green-100 leading-relaxed">
                        @if($item->status === 'PREGNANT')
                            Isolate the female in a maternity pen as the birth date approaches. Monitor nutrition intake and prepare for parturition (birth).
                        @else
                            Monitor for signs of lust (estrus) in the next 18-21 days. Consult with the vet if repeated failures occur.
                        @endif
                    </p>
                </div>
                <div class="hidden md:block">
                    @if($item->status === 'PREGNANT')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-500 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-500 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>
            </div>
            {{-- Background Decoration --}}
            <div class="absolute -right-10 -bottom-10 w-40 h-40 {{ $item->status === 'PREGNANT' ? 'bg-green-800' : 'bg-gray-700' }} rounded-full opacity-50"></div>
        </div>
    </div>
</div>
</div>