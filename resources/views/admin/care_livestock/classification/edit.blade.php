@extends('layouts.care_livestock.index')

@section('content')
<div class="p-6 sm:p-8">
    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Ubah Klasifikasi Ternak
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Mengubah klasifikasi untuk ternak dengan ID: <strong>{{ $livestock->eartag_number }}</strong>
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            {{-- DIUBAH: Nama route disesuaikan dengan yang ada di web.php --}}
            <a href="{{ route('admin.care-livestock.classification.index', $farm->id) }}"
               class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 font-semibold rounded-lg px-4 py-2 text-sm transition-all duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                {{-- DIUBAH: Teks disesuaikan --}}
                Kembali ke Daftar Klasifikasi
            </a>
        </div>
    </div>

    {{-- KARTU FORM --}}
    <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-10">
        <form action="{{ route('admin.care-livestock.classification.update', ['farm_id' => $farm->id, 'id' => $livestock->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-8">
                <label for="livestock_classification_id" class="block mb-2 text-sm font-medium text-gray-700">Pilih Klasifikasi Baru</label>
                <select id="livestock_classification_id" name="livestock_classification_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                    <option value="" disabled>-- Pilih Klasifikasi --</option>
                    @foreach ($classifications as $classification)
                        <option value="{{ $classification->id }}"
                                {{ old('livestock_classification_id', $livestock->livestock_classification_id) == $classification->id ? 'selected' : '' }}>
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
    </div>
</div>
@endsection
