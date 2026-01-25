<div> 
    <x-alert.session />

    {{-- Filter Section --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            {{-- Date Filters --}}
            <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="Start Date">
            <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500" placeholder="End Date">
            
            {{-- Pen Filter --}}
            <select wire:model.live="pen_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">All Pens</option>
                @foreach($pens as $pen)
                    <option value="{{ $pen->id }}">{{ $pen->name }}</option>
                @endforeach
            </select>

            {{-- Type Filter --}}
            <select wire:model.live="livestock_type_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">All Types</option>
                @foreach($livestockTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Add Button --}}
        <x-button.link href="{{ route('admin.care_livestock.pregnant_check.create', ['farm_id' => $farm->id]) }}" color="green">
            + Add Pregnant Check
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Date', 'class' => 'text-left'],
            ['label' => 'Livestock', 'class' => 'text-left'],
            ['label' => 'Officer', 'class' => 'text-left'],
            ['label' => 'Status', 'class' => 'text-center'],
            ['label' => 'Preg. Age', 'class' => 'text-center'],
            ['label' => 'Cost', 'class' => 'text-right'],
            ['label' => 'Action', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($items as $index => $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $index + 1 }}</td>
                
                {{-- Date --}}
                <td class="px-4 py-3 border-b text-sm">
                    {{ $item->pregnantCheck?->transaction_date ? date('d/m/Y', strtotime($item->pregnantCheck->transaction_date)) : '-' }}
                    <div class="text-xs text-gray-400">{{ $item->pregnantCheck?->transaction_number }}</div>
                </td>

                {{-- Livestock Info --}}
                <td class="px-4 py-3 border-b">
                    <div class="text-sm font-bold text-gray-900">
                        {{ $item->reproductionCycle?->livestock?->identification_number }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $item->reproductionCycle?->livestock?->nickname ?? '-' }} 
                        ({{ $item->reproductionCycle?->livestock?->pen?->name ?? 'No Pen' }})
                    </div>
                </td>

                {{-- Officer --}}
                <td class="px-4 py-3 border-b text-sm text-gray-700">
                    {{ $item->officer_name }}
                </td>

                {{-- Status --}}
                <td class="px-4 py-3 border-b text-center">
                    @if($item->status === 'PREGNANT')
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">
                            PREGNANT
                        </span>
                    @else
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">
                            NOT PREGNANT
                        </span>
                    @endif
                </td>

                {{-- Pregnant Age --}}
                <td class="px-4 py-3 border-b text-center text-sm font-semibold text-gray-700">
                    @if($item->status === 'PREGNANT')
                        {{ $item->pregnant_age }} <span class="text-xs font-normal text-gray-500">months</span>
                    @else
                        -
                    @endif
                </td>

                {{-- Cost --}}
                <td class="px-4 py-3 border-b text-right text-sm font-semibold text-gray-800">
                    Rp {{ number_format($item->cost, 0, ',', '.') }}
                </td>

                {{-- Actions --}}
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('admin.care_livestock.pregnant_check.show', ['farm_id' => $farm->id, 'id' => $item->id]) }}" color="gray">
                            Detail
                        </x-button.action>
                        
                        <x-button.action href="{{ route('admin.care_livestock.pregnant_check.edit', ['farm_id' => $farm->id, 'id' => $item->id]) }}" color="blue">
                            Edit
                        </x-button.action>
                        
                        <x-button.primary type="button" 
                            wire:click="delete({{ $item->id }})" 
                            wire:confirm="Are you sure? This will delete the check record and revert the livestock status." 
                            color="red" size="sm">
                            Delete
                        </x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="8" empty="No pregnant check data found." />
        @endforelse
    </x-table.wrapper>
</div>