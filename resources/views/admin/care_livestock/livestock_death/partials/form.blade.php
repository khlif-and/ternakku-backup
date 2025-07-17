{{-- Pesan error dengan gaya modern --}}
@if (session('error'))
    <div class="flex items-center p-4 mb-6 text-sm font-medium text-red-800 rounded-lg bg-red-100" role="alert">
        <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

@php
// Fix compact undefined variable
if (!isset($diseases)) $diseases = [];
@endphp

<form
    action="{{ route('admin.care-livestock.livestock-death.store', $farm->id) }}"
    method="POST"
    enctype="multipart/form-data"
    class="w-full max-w-full"
>
    @csrf

    {{-- Tanggal & Pilih Ternak --}}
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-8">
            <label for="tanggal-airdatepicker" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Kematian</label>
            <input id="tanggal-airdatepicker" name="transaction_date" type="text" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Pilih tanggal" autocomplete="off" value="{{ old('transaction_date') }}" required>
            @error('transaction_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label for="livestock_id" class="block mb-2 text-sm font-medium text-gray-700">Eartag / Nama Ternak</label>
<select id="livestock_id" name="livestock_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
    <option value="" selected disabled>Pilih Ternak dari Eartag</option>
    @foreach ($livestocks as $livestock)
        <option
            value="{{ $livestock->id }}"
            data-eartag="{{ $livestock->eartag ?? '-' }}"
            data-nama="{{ $livestock->name ?? '-' }}"
            data-jenis="{{ $livestock->livestockType->name ?? '-' }}"
            data-ras="{{ $livestock->livestockBreed->name ?? '-' }}"
        >
            {{ $livestock->eartag ?? '-' }} - {{ $livestock->name ?? '-' }}
            ({{ $livestock->livestockType->name ?? '-' }} - {{ $livestock->livestockBreed->name ?? '-' }})
        </option>
    @endforeach
</select>
            @error('livestock_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Otomatis Nama, Jenis, Ras --}}
    <div class="grid md:grid-cols-3 md:gap-6">
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Ternak</label>
            <input id="inputNamaTernak" type="text" value="(Akan terisi otomatis)" class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Ternak</label>
            <input id="inputJenisTernak" type="text" value="(Akan terisi otomatis)" class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
        <div class="mb-8">
            <label class="block mb-2 text-sm font-medium text-gray-700">Ras</label>
            <input id="inputRasTernak" type="text" value="(Akan terisi otomatis)" class="bg-gray-200 border border-gray-300 text-sm rounded-lg block w-full p-3 cursor-not-allowed" readonly>
        </div>
    </div>

    {{-- Penyakit --}}
    <div class="mb-8">
        <label for="disease_id" class="block mb-2 text-sm font-medium text-gray-700">Penyebab Kematian (Penyakit)</label>
        <select id="disease_id" name="disease_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3">
            <option value="" selected disabled>Pilih Penyakit (jika ada)</option>
            @foreach ($diseases as $id => $disease)
                <option value="{{ $id }}" @selected(old('disease_id') == $id)>{{ $disease }}</option>
            @endforeach
        </select>
        @error('disease_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Indikasi --}}
    <div class="mb-8">
        <label for="indication" class="block mb-2 text-sm font-medium text-gray-700">Indikasi</label>
        <input id="indication" type="text" name="indication" value="{{ old('indication') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" placeholder="Gejala, tanda, atau ciri-ciri...">
        @error('indication') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
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

{{-- Script isi otomatis --}}
<script>
document.getElementById('livestock_id').addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    document.getElementById('inputNamaTernak').value = selected.getAttribute('data-nama') || '';
    document.getElementById('inputJenisTernak').value = selected.getAttribute('data-jenis') || '';
    document.getElementById('inputRasTernak').value = selected.getAttribute('data-ras') || '';
});
</script>
