@extends('layouts.care_livestock.index')

@section('content')
    @php($farmId = request()->route('farm_id'))
    <div class="p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Tambah Artificial Inseminasi
                </h1>
                <nav class="mt-2 text-sm" aria-label="Breadcrumb">
                    {{-- Breadcrumb content --}}
                </nav>
            </div>

            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.care_livestock.artificial_inseminasi.index', ['farm_id' => $farmId]) }}"
                   class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 font-semibold rounded-lg px-4 py-2 text-sm transition-all duration-300 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-10">

            {{-- 🔹 Tambahkan blok ini --}}
            @if (session('error'))
                <div class="mb-6">
                    <div class="px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            {{-- 🔹 Livewire component --}}
            <livewire:admin.artificial-insemination.create-component :farm="$farm" />
        </div>
    </div>
@endsection
