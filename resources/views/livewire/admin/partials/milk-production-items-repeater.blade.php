<div class="bg-white rounded-lg border overflow-hidden">
    <div class="px-4 py-3 border-b bg-gray-50 flex items-center justify-between">
        <h3 class="font-semibold text-gray-700">Item Produksi Susu</h3>
        <button type="button" wire:click="addItem" class="text-sm bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg transition shadow-sm">
            + Tambah Shift
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Shift / Kategori</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Volume (Liter)</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase w-20">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($items as $index => $item)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" wire:model="items.{{ $index }}.name" 
                                class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Contoh: Pagi / Sore / Malam">
                            @error("items.{$index}.name") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" step="0.01" wire:model="items.{{ $index }}.volume" 
                                class="w-full px-3 py-2 border rounded-lg text-sm text-right focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="0.00">
                            @error("items.{$index}.volume") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if(count($items) > 1)
                                <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @else
                                <span class="text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>