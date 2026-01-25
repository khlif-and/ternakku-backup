<div> <x-alert.session />

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Sisi Kiri: Transaction Summary & Status --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-lg border p-5 space-y-4 shadow-sm">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold text-gray-800">Insemination Details</h3>
                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold uppercase tracking-wider">
                    NI No. {{ $niRecord->insemination_number }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Date</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $niRecord->insemination?->transaction_date ? date('d M Y', strtotime($niRecord->insemination->transaction_date)) : '-' }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Time</div>
                    <div class="text-sm font-semibold text-gray-800">
                        {{ $niRecord->action_time ? date('H:i', strtotime($niRecord->action_time)) : '-' }}
                    </div>
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Sire Owner</div>
                <div class="text-sm font-semibold text-gray-800">{{ $niRecord->sire_owner_name }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Action Cost</div>
                <div class="text-lg font-bold text-blue-700">
                    Rp {{ number_format($niRecord->cost ?? 0, 0, ',', '.') }}
                </div>
            </div>

            <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                <div class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Estimated Next Cycle</div>
                <div class="text-sm font-bold text-blue-800">
                    {{ $niRecord->cycle_date ? date('d M Y', strtotime($niRecord->cycle_date)) : 'Not determined' }}
                </div>
                <div class="text-[10px] text-blue-500 mt-1 italic">*If no signs of pregnancy appear.</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Notes</div>
                <div class="text-sm text-gray-800 italic">
                    {{ $niRecord->insemination?->notes ?: '-' }}
                </div>
            </div>

            <div class="pt-4 border-t space-y-2">
                <x-button.action href="{{ route('admin.care-livestock.natural-insemination.edit', [$farm->id, $niRecord->id]) }}" color="blue" class="w-full justify-center">
                    Edit Record
                </x-button.action>
                
                <x-button.primary wire:click="delete" wire:confirm="Are you sure you want to delete this NI record? This will remove the associated reproduction cycle history." color="red" class="w-full justify-center">
                    Delete Record
                </x-button.primary>
            </div>
        </div>

        {{-- Female Info --}}
        <div class="bg-white rounded-lg border p-5 shadow-sm">
            <div class="mb-4 font-bold text-gray-800 border-b pb-2 flex items-center gap-2">
                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                Female Profile
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">ID / Eartag</span>
                    <span class="text-sm font-bold text-gray-900">{{ $niRecord->reproductionCycle?->livestock?->identification_number }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">Nickname</span>
                    <span class="text-sm font-medium text-gray-800">{{ $niRecord->reproductionCycle?->livestock?->nickname ?: '-' }}</span>
                </div>
                <div class="flex items-center justify-between border-t pt-2">
                    <span class="text-xs text-gray-500">Current Pen</span>
                    <span class="text-sm font-medium text-gray-800">{{ $niRecord->reproductionCycle?->livestock?->pen?->name ?: '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Sire Details --}}
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b bg-gray-50 font-bold text-gray-700 flex items-center gap-2">
                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                Sire Information
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                    <div class="space-y-6">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Sire Breed</label>
                            <div class="flex items-center gap-2 text-lg font-bold text-gray-800">
                                <span class="w-2 h-6 bg-green-500 rounded-full"></span>
                                {{ $niRecord->sireBreed?->name ?? 'Not specified' }}
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Sire Name (if any)</label>
                            <div class="text-base font-semibold text-gray-700">{{ $niRecord->sire_name ?: 'Not recorded' }}</div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Sire Owner</label>
                            <div class="text-base font-semibold text-gray-700">{{ $niRecord->sire_owner_name ?: '-' }}</div>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                            <p class="text-xs text-green-700 leading-tight">
                                Natural breeding uses a live sire for mating. Ensure the sire is healthy and compatible with the female's breed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Next Steps Visual --}}
        <div class="bg-green-900 rounded-lg p-6 text-white shadow-md relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                <div class="flex-1">
                    <h4 class="text-lg font-bold mb-2">Next Steps?</h4>
                    <p class="text-sm text-green-100 leading-relaxed">
                        Monitor the female 18-21 days after the breeding date. If she does not return to estrus, schedule a pregnancy check (PKB) 60-90 days later to confirm success.
                    </p>
                </div>
                <div class="hidden md:block">
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" class="h-20 w-20 text-green-500 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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