<div>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Edit Penimbangan Ulang</h2>
        <x-button.link href="{{ route('admin.care-livestock.reweight.index', $farm->id) }}" color="gray">
            Kembali
        </x-button.link>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-6 max-w-2xl mx-auto">
        <form wire:submit.prevent="save" class="space-y-6">
            
            <x-form.input type="date" label="Tanggal Penimbangan" wire:model="transaction_date" name="transaction_date" required />

            {{-- Custom Livestock Selection --}}
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Pilih Ternak <span class="text-red-500">*</span></label>
                
                @if($selected_livestock_label)
                    <div class="flex items-center justify-between p-3 bg-gray-50 border rounded-md">
                        <span class="font-medium text-gray-900">{{ $selected_livestock_label }}</span>
                        <button type="button" wire:click="$set('selected_livestock_label', '')" class="text-red-500 text-sm hover:underline">Ganti</button>
                    </div>
                @else
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search_livestock" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Cari No. Eartag atau Nama..."
                        >
                        @if(!empty($livestocks))
                            <ul class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                @foreach($livestocks as $livestock)
                                    <li 
                                        wire:click="selectLivestock({{ $livestock->id }}, '{{ $livestock->eartag }} - {{ $livestock->name }}')"
                                        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 text-gray-900"
                                    >
                                        <div class="flex items-center">
                                            <span class="font-medium truncate mr-2">{{ $livestock->eartag }}</span>
                                            <span class="text-gray-500 truncate">{{ $livestock->name ? '('.$livestock->name.')' : '' }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @error('livestock_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                @endif
            </div>

            <x-form.input type="number" step="0.01" label="Berat (Kg)" wire:model="weight" name="weight" placeholder="Contoh: 350.5" required />

            <x-form.textarea label="Catatan" wire:model="notes" name="notes" placeholder="Catatan tambahan..." />
            
            <div class="space-y-2">
                <x-form.input type="file" label="Foto Bukti (Opsional)" wire:model="photo" name="photo" accept="image/*" />
                @if ($photo) 
                    <div class="mt-2">
                        <p class="text-xs text-gray-500 mb-1">Preview Foto Baru:</p>
                        <img src="{{ $photo->temporaryUrl() }}" class="h-40 rounded-lg border object-cover">
                    </div>
                @elseif($current_photo)
                    <div class="mt-2">
                        <p class="text-xs text-gray-500 mb-1">Foto Saat Ini:</p>
                        <img src="{{ $current_photo }}" class="h-40 rounded-lg border object-cover">
                    </div>
                @endif
            </div>

            <div class="pt-4 flex justify-end">
                <x-button.primary type="submit">Simpan Perubahan</x-button.primary>
            </div>
        </form>
    </div>
</div>
