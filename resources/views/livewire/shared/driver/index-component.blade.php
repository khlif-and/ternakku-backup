<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="text" wire:model.live.debounce.500ms="search"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500"
                placeholder="Cari nama, email, atau no. HP...">
        </div>
        <x-button.link href="{{ route('shared.driver.create', $farm->id) }}" color="green">
            + Tambah Pengemudi
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Nama', 'class' => 'text-left'],
            ['label' => 'Email', 'class' => 'text-left'],
            ['label' => 'No. HP', 'class' => 'text-left'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($drivers as $index => $driver)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $drivers->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm font-medium text-gray-900">
                    {{ $driver->user->name ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $driver->user->email ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b text-sm">
                    {{ $driver->user->phone_number ?? '-' }}
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action href="{{ route('shared.driver.show', [$farm->id, $driver->id]) }}"
                            color="gray">Detail</x-button.action>
                        <x-button.action href="{{ route('shared.driver.edit', [$farm->id, $driver->id]) }}"
                            color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $driver->id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus pengemudi ini?" color="red"
                            size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="5" empty="Tidak ada data pengemudi ditemukan." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $drivers->links() }}
    </div>
</div>