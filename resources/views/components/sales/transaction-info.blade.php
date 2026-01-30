@props(['transactionAccount', 'orderDate', 'description', 'editUrl', 'deleteAction'])

<div class="bg-white rounded-lg border p-5 space-y-4 shadow-sm">
    <div class="flex items-center justify-between border-b pb-3">
        <h3 class="font-bold text-gray-800">Informasi Transaksi</h3>
        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase tracking-wider">
            {{ $transactionAccount }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tanggal Order</div>
            <div class="text-sm font-semibold text-gray-800">
                {{ date('d M Y', strtotime($orderDate)) }}
            </div>
        </div>
    </div>

    <div>
        <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Deskripsi</div>
        <div class="text-sm text-gray-800 italic">
            {{ $description ?: '-' }}
        </div>
    </div>

    {{ $slot ?? '' }}

    <div class="pt-4 border-t space-y-2">
        <x-button.action href="{{ $editUrl }}" color="blue" class="w-full justify-center">
            Edit Order
        </x-button.action>
        
        <x-button.primary type="button" wire:click="{{ $deleteAction }}" wire:confirm="Apakah Anda yakin ingin menghapus Sales Order ini?" color="red" class="w-full justify-center">
            Hapus Order
        </x-button.primary>
    </div>
</div>
