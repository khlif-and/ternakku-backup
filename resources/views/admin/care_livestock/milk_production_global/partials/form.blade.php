{{-- Pesan error dengan gaya modern --}}
@if (session('error'))
    <div class="flex items-center p-4 mb-6 text-sm font-medium text-red-800 rounded-lg bg-red-100" role="alert">
        <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

<form
    action="{{ route('admin.care-livestock.milk-production-global.store', $farm->id) }}"
    method="POST"
    enctype="multipart/form-data"
    class="w-full max-w-full"
>
    @csrf

    {{-- Tanggal Produksi & Shift --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-8">
            <label for="tanggal-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Produksi</label>
            <input id="tanggal-airdatepicker" name="transaction_date" type="text" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date') }}" required>
            @error('transaction_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label for="milking_shift" class="block mb-2 text-sm font-medium text-gray-700">Shift Pemerahan</label>
            <select id="milking_shift" name="milking_shift" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                <option value="" disabled {{ old('milking_shift') == '' ? 'selected' : '' }}>Pilih Shift</option>
                <option value="morning" {{ old('milking_shift') == 'morning' ? 'selected' : '' }}>Pagi</option>
                <option value="afternoon" {{ old('milking_shift') == 'afternoon' ? 'selected' : '' }}>Sore</option>
            </select>
            @error('milking_shift') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Waktu, Nama Pemerah, Jumlah Susu --}}
    <div class="grid md:grid-cols-3 md:gap-6">
        <div class="mb-8">
            <label for="milking_time" class="block mb-2 text-sm font-medium text-gray-700">Waktu Pemerahan</label>
            <input id="milking_time" type="text" name="milking_time" value="{{ old('milking_time') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Contoh: 06:00" required>
            @error('milking_time') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label for="milker_name" class="block mb-2 text-sm font-medium text-gray-700">Nama Pemerah</label>
            <input id="milker_name" type="text" name="milker_name" value="{{ old('milker_name') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Nama petugas" required>
            @error('milker_name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label for="quantity_liters" class="block mb-2 text-sm font-medium text-gray-700">Jumlah Susu (liter)</label>
            <input id="quantity_liters" type="number" min="0" step="0.01" name="quantity_liters" value="{{ old('quantity_liters') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="0.00" required>
            @error('quantity_liters') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Kondisi Susu --}}
    <div class="mb-8">
        <label for="milk_condition" class="block mb-2 text-sm font-medium text-gray-700">Kondisi Susu</label>
        <input id="milk_condition" type="text" name="milk_condition" value="{{ old('milk_condition') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Baik / Asam / dll">
        @error('milk_condition') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Catatan --}}
    <div class="mb-8">
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan (opsional)</label>
        <textarea id="notes" name="notes" rows="4" class="block w-full text-sm border border-gray-300 rounded-lg p-3 bg-gray-50" placeholder="Tambahkan catatan jika perlu...">{{ old('notes') }}</textarea>
        @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Tombol Simpan --}}
    <div class="flex justify-end mt-4">
        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-base px-8 py-3 transition-all">Simpan Data</button>
    </div>
</form>
