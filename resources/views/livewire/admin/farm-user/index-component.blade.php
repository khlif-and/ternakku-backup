<div>
    <x-alert.session />

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <input type="text" wire:model.live.debounce.500ms="search"
                class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500"
                placeholder="Cari nama, email, telepon...">
            <select wire:model.live="filterRole" class="px-4 py-2 border rounded-lg text-sm focus:ring-blue-500">
                <option value="">Semua Role</option>
                <option value="ABK">ABK</option>
                <option value="ADMIN">ADMIN</option>
                <option value="DRIVER">DRIVER</option>
                <option value="MARKETING">MARKETING</option>
            </select>
        </div>
        <x-button.link href="{{ route('admin.care-livestock.farm-users.create', $farm->id) }}" color="green">
            + Tambah Pengguna
        </x-button.link>
    </div>

    @php
        $headers = [
            ['label' => 'No', 'class' => 'text-left w-16'],
            ['label' => 'Nama', 'class' => 'text-left'],
            ['label' => 'Email', 'class' => 'text-left'],
            ['label' => 'Telepon', 'class' => 'text-left'],
            ['label' => 'Role', 'class' => 'text-center'],
            ['label' => 'Aksi', 'class' => 'text-center'],
        ];
    @endphp

    <x-table.wrapper :headers="$headers">
        @forelse($farmUsers as $index => $farmUser)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 border-b text-sm">{{ $farmUsers->firstItem() + $index }}</td>
                <td class="px-4 py-3 border-b text-sm font-medium text-gray-900">{{ $farmUser->user->name ?? '-' }}</td>
                <td class="px-4 py-3 border-b text-sm">{{ $farmUser->user->email ?? '-' }}</td>
                <td class="px-4 py-3 border-b text-sm">{{ $farmUser->user->phone_number ?? '-' }}</td>
                <td class="px-4 py-3 border-b text-sm text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $farmUser->farm_role === 'ADMIN' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $farmUser->farm_role === 'ABK' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $farmUser->farm_role === 'DRIVER' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $farmUser->farm_role === 'MARKETING' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        ">
                        {{ $farmUser->farm_role }}
                    </span>
                </td>
                <td class="px-4 py-3 border-b">
                    <div class="flex items-center justify-center gap-2">
                        <x-button.action
                            href="{{ route('admin.care-livestock.farm-users.show', [$farm->id, $farmUser->id]) }}"
                            color="gray">Detail</x-button.action>
                        <x-button.action
                            href="{{ route('admin.care-livestock.farm-users.edit', [$farm->id, $farmUser->id]) }}"
                            color="blue">Edit</x-button.action>
                        <x-button.primary type="button" wire:click="delete({{ $farmUser->id }})"
                            wire:confirm="Yakin ingin menghapus pengguna ini dari farm?" color="red"
                            size="sm">Hapus</x-button.primary>
                    </div>
                </td>
            </tr>
        @empty
            <x-table.empty colspan="6" empty="Belum ada data pengguna." />
        @endforelse
    </x-table.wrapper>

    <div class="mt-4">
        {{ $farmUsers->links() }}
    </div>
</div>