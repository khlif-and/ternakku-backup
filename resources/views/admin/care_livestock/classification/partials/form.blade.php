{{-- Pesan error modern --}}
@if (session('error'))
    <div class="flex items-center p-4 mb-6 text-sm font-medium text-red-800 rounded-lg bg-red-100" role="alert">
        <svg class="w-5 h-5 me-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

{{-- DIUBAH: Action form disesuaikan untuk update klasifikasi --}}
<form
    action="{{ route('admin.care-livestock.classification.update', ['farm_id' => $farm->id, 'livestock_id' => $livestock->id]) }}"
    method="POST"
    enctype="multipart/form-data"
    class="w-full max-w-full"
>
    @csrf
    {{-- DIUBAH: @method('PUT') dihapus agar form murni menggunakan POST --}}

    {{-- Menampilkan informasi ternak yang sedang diubah --}}
    <div class="mb-6">
        <p class="text-sm text-gray-600">Anda sedang mengubah klasifikasi untuk ternak:</p>
        <p class="text-lg font-semibold text-gray-800">{{ $livestock->eartag_number }}</p>
    </div>

    {{-- Dropdown klasifikasi --}}
    <div class="mb-8">
        <label for="livestock_classification_id" class="block mb-2 text-sm font-medium text-gray-700">Pilih Klasifikasi Baru</label>
        <select id="livestock_classification_id" name="livestock_classification_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
            <option value="" disabled>-- Pilih Klasifikasi --</option>
            {{-- Controller akan mengirimkan variabel $classifications --}}
            @foreach ($classifications as $classification)
                <option value="{{ $classification->id }}"
                        {{ old('livestock_classification_id', $livestock->livestock_classification_id) == $classification->id ? 'selected' : '' }}>
                    {{-- Asumsi kolom nama adalah 'name' --}}
                    {{ $classification->name }}
                </option>
            @endforeach
        </select>
        @error('livestock_classification_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Tombol Simpan --}}
    <div class="flex justify-end mt-4">
        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-base px-8 py-3 transition-all">
            Simpan Perubahan
        </button>
    </div>
</form>
