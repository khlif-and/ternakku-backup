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
    action="{{ route('admin.care-livestock.livestock-sale-weight.store', $farm->id) }}"
    method="POST"
    enctype="multipart/form-data"
    class="w-full max-w-full"
>
    @csrf

    {{-- Tanggal dan Customer --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-8">
            <label for="tanggal-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Penjualan</label>
            <input id="tanggal-airdatepicker" name="transaction_date" type="text" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date') }}" required>
            @error('transaction_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label for="customer" class="block mb-2 text-sm font-medium text-gray-700">Customer</label>
            <input id="customer" type="text" name="customer" value="{{ old('customer') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Nama pembeli" required>
            @error('customer') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Livestock Dropdown --}}
    <div class="mb-8">
        <label for="livestock_id" class="block mb-2 text-sm font-medium text-gray-700">Eartag / Livestock</label>
        <select id="livestock_id" name="livestock_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
            <option value="" selected disabled>Pilih Ternak dari Eartag</option>
@foreach ($livestockSaleWeight as $livestock)
<option
    value="{{ $livestock->id }}"
    data-ternak="{{ $livestock->eartag_number ?? '-' }}"
    data-rfid="{{ $livestock->rfid_number ?? '-' }}"
    data-jenis="{{ $livestock->livestockType->name ?? '-' }}"
    data-ras="{{ $livestock->livestockBreed->name ?? '-' }}"
>
    {{ $livestock->eartag_number ?? '-' }} - {{ $livestock->livestockType->name ?? '-' }} ({{ $livestock->livestockBreed->name ?? '-' }})
</option>
@endforeach




        </select>
        @error('livestock_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Otomatis Ternak & Jenis --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Ternak</label>
            <input id="inputTernak" type="text" value="(Akan terisi otomatis)" class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Ternak</label>
            <input id="inputJenisTernak" type="text" value="(Akan terisi otomatis)" class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
    </div>

    {{-- Berat & Harga --}}
    <div class="grid md:grid-cols-3 md:gap-6">
        <div class="mb-8">
            <label for="weight" class="block mb-2 text-sm font-medium text-gray-700">Berat (kg)</label>
            <input id="weight" type="number" step="0.01" name="weight" value="{{ old('weight') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Contoh: 450.5" required>
            @error('weight') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label for="price_per_kg" class="block mb-2 text-sm font-medium text-gray-700">Harga per Kg</label>
            <input id="price_per_kg" type="number" step="0.01" name="price_per_kg" value="{{ old('price_per_kg') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Contoh: 55000" required>
            @error('price_per_kg') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label for="price_per_head" class="block mb-2 text-sm font-medium text-gray-700">Total Harga</label>
            <input id="price_per_head" type="number" step="0.01" name="price_per_head" value="{{ old('price_per_head') }}" class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
            @error('price_per_head') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Foto --}}
    <div class="mb-8">
        <label for="photo" class="block mb-2 text-sm font-medium text-gray-700">Foto Ternak (opsional)</label>
        <input name="photo" accept="image/*" id="photo" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:bg-gray-100 file:border-0 file:px-4 file:py-3 hover:file:bg-gray-200">
        @error('photo') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Catatan --}}
    <div class="mb-8">
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan (opsional)</label>
        <textarea id="notes" name="notes" rows="4" class="block w-full text-sm border border-gray-300 rounded-lg p-3 bg-gray-50" placeholder="Tambahkan catatan jika perlu...">{{ old('notes') }}</textarea>
        @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Tombol Simpan --}}
    <div class="flex justify-end mt-4">
        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-base px-8 py-3 transition-all">Simpan Data</button>
    </div>
</form>

{{-- Script isi otomatis Ternak & Jenis --}}
<script>
document.getElementById('livestock_id').addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const ras = selected.getAttribute('data-ras') || '';        // ← ambil nama ras!
    const jenis = selected.getAttribute('data-jenis') || '';

    document.getElementById('inputTernak').value = ras;          // ← isikan ke field "Ternak"
    document.getElementById('inputJenisTernak').value = jenis;
});
</script>

