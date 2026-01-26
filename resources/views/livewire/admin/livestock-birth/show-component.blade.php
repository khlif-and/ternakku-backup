<div> 
    <x-alert.session />

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Sisi Kiri: Transaction Summary & Officer --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg border p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-gray-800">Birth Details</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase tracking-wider">
                        #{{ $birth->id }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Transaction Date</div>
                        <div class="text-sm font-semibold text-gray-800">
                            {{ $birth->transaction_date ? date('d M Y', strtotime($birth->transaction_date)) : '-' }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Officer Name</div>
                    <div class="text-sm font-semibold text-gray-800">{{ $birth->officer_name ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Cost</div>
                    <div class="text-lg font-bold text-blue-700">
                        Rp {{ number_format($birth->cost ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Notes</div>
                    <div class="text-sm text-gray-800 italic">
                        {{ $birth->notes ?: '-' }}
                    </div>
                </div>

                <div class="pt-4 border-t space-y-2">
                    <x-button.action href="{{ route('admin.care_livestock.livestock_birth.edit', ['farm_id' => $farm->id, 'id' => $birth->id]) }}" color="blue" class="w-full justify-center">
                        Edit Record
                    </x-button.action>
                    
                    <x-button.primary wire:click="delete" wire:confirm="Are you sure? This will delete the birth record and revert the livestock status." color="red" class="w-full justify-center">
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
                    Mother (Dam) Profile
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">ID / Eartag</span>
                        <span class="text-sm font-bold text-gray-900">{{ $birth->reproductionCycle->livestock->identification_number }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Nickname</span>
                        <span class="text-sm font-medium text-gray-800">{{ $birth->reproductionCycle->livestock->nickname ?: '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t pt-2">
                        <span class="text-xs text-gray-500">Current Pen</span>
                        <span class="text-sm font-medium text-gray-800">{{ $birth->reproductionCycle->livestock->pen->name ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Birth Results --}}
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b bg-gray-50 font-bold text-gray-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Birth Outcome
                </div>

                <div class="p-6">
                    {{-- Status Banner --}}
                    <div class="mb-8 text-center">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">Birth Status</label>
                        @if($birth->status === 'NORMAL')
                            <div class="inline-flex items-center justify-center px-6 py-2 rounded-full bg-green-100 text-green-700 border border-green-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xl font-bold tracking-wide">NORMAL BIRTH</span>
                            </div>
                        @elseif($birth->status === 'PREMATURE')
                            <div class="inline-flex items-center justify-center px-6 py-2 rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="text-xl font-bold tracking-wide">PREMATURE BIRTH</span>
                            </div>
                        @else
                            <div class="inline-flex items-center justify-center px-6 py-2 rounded-full bg-red-100 text-red-700 border border-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xl font-bold tracking-wide">ABORTUS (FAILED)</span>
                            </div>
                        @endif
                    </div>

                    {{-- Offspring Stats --}}
                    @if($birth->status !== 'ABORTUS')
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 border-b pb-6">
                            <div class="text-center p-3 bg-gray-50 rounded">
                                <div class="text-xs text-gray-500 uppercase">Total Offspring</div>
                                <div class="text-2xl font-bold text-gray-800">{{ $birth->livestockBirthD->count() }}</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded">
                                <div class="text-xs text-green-600 uppercase">Alive</div>
                                <div class="text-2xl font-bold text-green-700">{{ $birth->livestockBirthD->where('status', 'alive')->count() }}</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded">
                                <div class="text-xs text-red-600 uppercase">Dead</div>
                                <div class="text-2xl font-bold text-red-700">{{ $birth->livestockBirthD->where('status', 'dead')->count() }}</div>
                            </div>
                             <div class="text-center p-3 bg-blue-50 rounded">
                                <div class="text-xs text-blue-600 uppercase">Weaning Est.</div>
                                <div class="text-sm font-bold text-blue-700 mt-2">
                                    {{ $birth->estimated_weaning ? date('d M Y', strtotime($birth->estimated_weaning)) : '-' }}
                                </div>
                            </div>
                        </div>

                        {{-- Offspring List Table --}}
                        <div class="overflow-x-auto">
                            <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase">Offspring Details</h4>
                            <table class="w-full text-sm text-left text-gray-500 border rounded-lg overflow-hidden">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3">Order</th>
                                        <th class="px-4 py-3">Sex</th>
                                        <th class="px-4 py-3">Breed</th>
                                        <th class="px-4 py-3 text-right">Weight (kg)</th>
                                        <th class="px-4 py-3 text-center">Condition</th>
                                        <th class="px-4 py-3 text-right">Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($birth->livestockBirthD as $detail)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">#{{ $detail->birth_order }}</td>
                                        <td class="px-4 py-3">
                                            @if($detail->livestock_sex_id === \App\Enums\LivestockSexEnum::JANTAN->value)
                                                <span class="inline-flex items-center text-blue-600 bg-blue-50 px-2 py-1 rounded text-xs">
                                                    ♂ Male
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-pink-600 bg-pink-50 px-2 py-1 rounded text-xs">
                                                    ♀ Female
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $detail->livestockBreed->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right font-semibold">{{ $detail->weight }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($detail->status === 'alive')
                                                <span class="text-green-600 bg-green-100 px-2 py-1 rounded-full text-xs font-bold">Alive</span>
                                            @else
                                                <span class="text-red-600 bg-red-100 px-2 py-1 rounded-full text-xs font-bold">Dead</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right text-xs">
                                            @if($detail->status === 'alive')
                                                Val: Rp {{ number_format($detail->offspring_value ?? 0, 0, ',', '.') }}
                                            @else
                                                <span class="text-red-500">{{ $detail->indication ?? 'Unknown cause' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 bg-red-50 rounded-lg border border-red-100 text-center">
                            <p class="text-red-700 font-medium">
                                Pregnancy failed (Abortus). No offspring details available.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Next Steps Visual --}}
            <div class="{{ $birth->status !== 'ABORTUS' ? 'bg-green-900' : 'bg-gray-800' }} rounded-lg p-6 text-white shadow-md relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-1">
                        <h4 class="text-lg font-bold mb-2">Next Steps?</h4>
                        <p class="text-sm text-green-100 leading-relaxed">
                            @if($birth->status !== 'ABORTUS')
                                Monitor the mother's recovery and ensure adequate milk production. Ensure offspring receive colostrum within the first 6 hours.
                            @else
                                Isolate the mother to prevent potential disease spread. Consult a veterinarian to determine the cause of abortion before re-mating.
                            @endif
                        </p>
                    </div>
                    <div class="hidden md:block">
                        @if($birth->status !== 'ABORTUS')
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
                <div class="absolute -right-10 -bottom-10 w-40 h-40 {{ $birth->status !== 'ABORTUS' ? 'bg-green-800' : 'bg-gray-700' }} rounded-full opacity-50"></div>
            </div>
        </div>
    </div>
</div>