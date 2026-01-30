@props(['name', 'phone'])

<div class="bg-white rounded-lg border p-5 shadow-sm">
    <div class="mb-4 font-bold text-gray-800 border-b pb-2 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Informasi Pelanggan
    </div>
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500">Nama Pelanggan</span>
            <span class="text-sm font-bold text-gray-900">{{ $name ?? '-' }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500">No. Telepon</span>
            <span class="text-sm font-medium text-gray-800">{{ $phone ?? '-' }}</span>
        </div>
    </div>
</div>
