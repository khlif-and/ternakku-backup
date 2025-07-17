@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Tambah Registrasi Ternak ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Care Livestock</li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Tambah Registrasi</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ route('admin.care-livestock.livestock-reception.index', $farm->id) }}"
                    class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow transition-all font-sans">
                    Kembali ke Daftar Registrasi
                </a>
            </div>

            <div class="px-16 py-8">
                @include('admin.care_livestock.livestock_reception.partials.form')
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- AirDatepicker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script>

    {{-- Alpine.js (wajib kalau dropdown kamu pakai alpine) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Livestock Reception Scripts --}}
    <script>
        window.oldLivestockTypeId = "{{ old('livestock_type_id') ?? '' }}";
    </script>

    <script src="{{ asset('admin/livestock-reception/breed.js') }}"></script>
    <script src="{{ asset('admin/livestock-reception/livestock-reception.js') }}"></script>
@endsection

