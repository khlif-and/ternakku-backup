<div>
    {{-- Error Messages --}}
    @if (session('error'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-red-100 border border-red-400 text-red-700 font-medium">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <p class="font-semibold mb-2">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="save" class="w-full">
        {{-- Row 1: Tanggal & Foto --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Tanggal Transaksi</label>
                <input type="date" wire:model="transaction_date"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('transaction_date') border-red-500 @enderror" required>
                @error('transaction_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Foto (opsional)</label>
                @if($existing_photo)
                    <div class="mb-2">
                        <img src="{{ asset($existing_photo) }}" alt="Current photo" class="w-20 h-20 object-cover rounded">
                    </div>
                @endif
                <input type="file" wire:model="photo" accept="image/*"
                    class="w-full border px-4 py-3 rounded-lg text-base">
                @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                <div wire:loading wire:target="photo" class="text-sm text-blue-500 mt-1">Uploading...</div>
            </div>
        </div>

        {{-- Supplier --}}
        <div class="mb-8">
            <label class="block mb-2 text-base font-semibold text-gray-700">Supplier (opsional)</label>
            <input type="text" wire:model="supplier"
                class="w-full px-4 py-3 border rounded-lg text-base">
        </div>

        {{-- Eartag & RFID --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Eartag Number</label>
                <input type="text" wire:model="eartag_number"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('eartag_number') border-red-500 @enderror" required>
                @error('eartag_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">RFID Number (opsional)</label>
                <input type="text" wire:model="rfid_number"
                    class="w-full px-4 py-3 border rounded-lg text-base">
            </div>
        </div>

        {{-- Jenis, Kelamin, Grup --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Jenis Ternak</label>
                <select wire:model.live="livestock_type_id"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('livestock_type_id') border-red-500 @enderror" required>
                    <option value="">Pilih Jenis Ternak</option>
                    @foreach($livestockTypes as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('livestock_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Jenis Kelamin</label>
                <select wire:model="livestock_sex_id"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('livestock_sex_id') border-red-500 @enderror" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    @foreach($sexes as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('livestock_sex_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Grup</label>
                <select wire:model="livestock_group_id"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('livestock_group_id') border-red-500 @enderror" required>
                    <option value="">Pilih Grup</option>
                    @foreach($groups as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('livestock_group_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Ras, Klasifikasi, Kandang --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Ras</label>
                <select wire:model="livestock_breed_id"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('livestock_breed_id') border-red-500 @enderror" required>
                    <option value="">{{ $livestock_type_id ? 'Pilih Ras' : 'Pilih jenis ternak dulu' }}</option>
                    @foreach($breeds as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('livestock_breed_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Klasifikasi</label>
                <select wire:model="livestock_classification_id"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('livestock_classification_id') border-red-500 @enderror" required>
                    <option value="">Pilih Klasifikasi</option>
                    @foreach($classifications as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('livestock_classification_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Kandang</label>
                <select wire:model="pen_id"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('pen_id') border-red-500 @enderror" required>
                    <option value="">Pilih Kandang</option>
                    @foreach($pens as $pen)
                        <option value="{{ $pen->id }}">{{ $pen->name }} (Kapasitas: {{ $pen->capacity }})</option>
                    @endforeach
                </select>
                @error('pen_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Usia --}}
        <div class="mb-8">
            <label class="block mb-2 text-base font-semibold text-gray-700">Usia (Tahun / Bulan)</label>
            <div class="flex gap-4">
                <input type="number" wire:model="age_years" min="0" placeholder="Tahun"
                    class="w-1/2 px-4 py-3 border rounded-lg text-base">
                <input type="number" wire:model="age_months" min="0" max="11" placeholder="Bulan"
                    class="w-1/2 px-4 py-3 border rounded-lg text-base">
            </div>
        </div>

        {{-- Berat & Harga --}}
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Berat (kg)</label>
                <input type="number" step="0.01" wire:model.live="weight"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('weight') border-red-500 @enderror" required>
                @error('weight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Harga per Kg</label>
                <input type="number" step="0.01" wire:model.live="price_per_kg"
                    class="w-full px-4 py-3 border rounded-lg text-base @error('price_per_kg') border-red-500 @enderror" required>
                @error('price_per_kg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-2 text-base font-semibold text-gray-700">Harga per Kepala</label>
                <input type="number" wire:model="price_per_head" readonly
                    class="w-full px-4 py-3 border rounded-lg text-base bg-gray-100">
            </div>
        </div>

        {{-- Catatan --}}
        <div class="mb-8">
            <label class="block mb-2 text-base font-semibold text-gray-700">Catatan (opsional)</label>
            <textarea wire:model="notes" rows="3"
                class="w-full px-4 py-3 border rounded-lg text-base"></textarea>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end mt-8 gap-3">
            <a href="{{ route('admin.care-livestock.livestock-reception.index', $farm->id) }}"
                class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-all disabled:opacity-50"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Perubahan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
