@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-6 sm:p-8">
        {{-- HEADER HALAMAN: Judul, Breadcrumbs, dan Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            {{-- Kiri: Judul dan Breadcrumbs --}}
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    {{-- DIUBAH: Judul disesuaikan --}}
                    Ubah Klasifikasi Ternak
                </h1>
                {{-- Breadcrumbs dengan ikon SVG modern --}}
                <nav class="mt-2 text-sm" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="/" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500">Care Livestock</span>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                {{-- DIUBAH: Teks breadcrumb disesuaikan --}}
                                <span class="ms-1 text-sm font-medium text-gray-700">
                                   Ubah Klasifikasi Ternak
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            {{-- Kanan: Tombol Aksi --}}
            <div class="mt-4 sm:mt-0">
                {{-- DIUBAH: Route tombol kembali ke daftar ternak --}}
                {{-- ASUMSI: Ganti 'admin.management.livestocks.index' dengan route daftar ternak Anda --}}
                <a href="{{ route('admin.management.livestocks.index', $farm->id) }}"
                   class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 font-semibold rounded-lg px-4 py-2 text-sm transition-all duration-300 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                     Kembali ke Daftar Ternak
                </a>
            </div>
        </div>

        {{-- KARTU KONTEN UTAMA: berisi form --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-10">
            {{-- DIUBAH: Isi konten diubah menjadi form untuk Update Classification --}}
            {{-- ASUMSI: Route ini akan Anda buat di web.php --}}
            <form action="{{ route('admin.care-livestock.classification.update', ['farm_id' => $farm->id, 'livestock_id' => $livestock->id]) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Menampilkan ID Ternak yang sedang diubah --}}
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Anda sedang mengubah klasifikasi untuk ternak:</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $livestock->eartag_number }}</p>
                </div>

                {{-- Dropdown Klasifikasi --}}
                <div class="mb-8">
                    <label for="livestock_classification_id" class="block mb-2 text-sm font-medium text-gray-700">Pilih Klasifikasi Baru</label>
                    <select id="livestock_classification_id" name="livestock_classification_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-3" required>
                        <option value="" disabled>-- Pilih Klasifikasi --</option>
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
        </div>
    </div>
@endsection

{{-- Section script dikosongkan karena form ini tidak memerlukan JS khusus --}}
@section('script')
@endsection
