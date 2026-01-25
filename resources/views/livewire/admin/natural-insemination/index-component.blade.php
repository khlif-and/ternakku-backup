<div> <x-alert.session />

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div class="flex flex-wrap gap-3">
        <input type="date" wire:model.live="start_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
        <input type="date" wire:model.live="end_date" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
        
        <select wire:model.live="livestock_id" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
            <option value="">All Females</option>
            @foreach($livestocks as $ls)
                <option value="{{ $ls->id }}">{{ $ls->identification_number }} - {{ $ls->nickname }}</option>
            @endforeach
        </select>
    </div>
    <x-button.link href="{{ route('admin.care-livestock.natural-insemination.create', $farm->id) }}" color="green">
        + Add Natural Insemination
    </x-button.link>
</div>

@php
    $headers = [
        ['label' => 'No', 'class' => 'text-left w-16'],
        ['label' => 'Date', 'class' => 'text-left'],
        ['label' => 'Female', 'class' => 'text-left'],
        ['label' => 'Sire Breed', 'class' => 'text-left'],
        ['label' => 'Sire Owner', 'class' => 'text-left'],
        ['label' => 'No.', 'class' => 'text-center'],
        ['label' => 'Cost', 'class' => 'text-left'],
        ['label' => 'Action', 'class' => 'text-center'],
    ];
@endphp

<x-table.wrapper :headers="$headers">
    @forelse($items as $index => $item)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 border-b text-sm">{{ $index + 1 }}</td>
            <td class="px-4 py-3 border-b text-sm">
                {{ $item->insemination?->transaction_date ? date('d/m/Y', strtotime($item->insemination->transaction_date)) : '-' }}
            </td>
            <td class="px-4 py-3 border-b">
                <div class="text-sm font-bold text-gray-900">{{ $item->reproductionCycle?->livestock?->identification_number }}</div>
                <div class="text-xs text-gray-500">{{ $item->reproductionCycle?->livestock?->nickname }}</div>
            </td>
            <td class="px-4 py-3 border-b">
                <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-semibold border border-green-100">
                    {{ $item->sireBreed?->name ?? '-' }}
                </span>
            </td>
            <td class="px-4 py-3 border-b text-sm text-gray-700">
                {{ $item->sire_owner_name }}
            </td>
            <td class="px-4 py-3 border-b text-center text-sm font-semibold">
                {{ $item->insemination_number }}
            </td>
            <td class="px-4 py-3 border-b text-sm font-semibold text-gray-800">
                Rp {{ number_format($item->cost, 0, ',', '.') }}
            </td>
            <td class="px-4 py-3 border-b">
                <div class="flex items-center justify-center gap-2">
                    <x-button.action href="{{ route('admin.care-livestock.natural-insemination.show', [$farm->id, $item->id]) }}" color="gray">Detail</x-button.action>
                    <x-button.action href="{{ route('admin.care-livestock.natural-insemination.edit', [$farm->id, $item->id]) }}" color="blue">Edit</x-button.action>
                    <x-button.primary type="button" wire:click="delete({{ $item->id }})" wire:confirm="Are you sure you want to delete this record? This will also delete the associated reproduction cycle." color="red" size="sm">Delete</x-button.primary>
                </div>
            </td>
        </tr>
    @empty
        <x-table.empty colspan="8" empty="No natural insemination data found." />
    @endforelse
</x-table.wrapper>
</div>