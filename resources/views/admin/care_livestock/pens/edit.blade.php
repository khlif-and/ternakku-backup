@extends('layouts.care_livestock.index')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <p class="text-gray-700 text-lg mb-3 font-semibold">[ Edit Kandang ]</p>
        <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
            <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
            <li><i class="icon-arrow-right"></i></li>
            <li>Care Livestock</li>
            <li><i class="icon-arrow-right"></i></li>
            <li>Edit Kandang</li>
        </ul>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
        <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
            <a href="{{ route('admin.care-livestock.pens.index', $farm->id) }}"
               class="bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow transition-all font-sans">
                Kembali ke Daftar Kandang
            </a>
        </div>

        <div class="px-16 py-8">
            @if (session('error'))
                <div class="mb-6 px-4 py-3 rounded bg-red-100 border border-red-400 text-red-700 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.care-livestock.pens.update', [$farm->id, $pen->id]) }}" method="POST" enctype="multipart/form-data" class="w-full max-w-full">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-8">
                    <label for="name" class="block mb-2 text-base font-semibold text-gray-700">Nama Kandang</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $pen->name) }}"
                        class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" required>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Area --}}
                <div class="mb-8">
                    <label for="area" class="block mb-2 text-base font-semibold text-gray-700">Luas Area (m²)</label>
                    <input type="text" name="area" id="area"
                        value="{{ old('area', $pen->area) }}"
                        class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" required>
                    @error('area') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Kapasitas --}}
                <div class="mb-8">
                    <label for="capacity" class="block mb-2 text-base font-semibold text-gray-700">Kapasitas (ekor)</label>
                    <input type="number" name="capacity" id="capacity"
                        value="{{ old('capacity', $pen->capacity) }}"
                        class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" required>
                    @error('capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Ganti Foto --}}
                <div class="mb-8">
                    <label for="photo" class="block mb-2 text-base font-semibold text-gray-700">Foto Kandang (opsional)</label>
                    <input type="file" name="photo" id="photo" accept="image/*"
                        class="w-full text-base border rounded-md px-3 py-2 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                    @if ($pen->photo)
                        <div class="mt-3">
                            <img src="{{ $pen->photo }}" alt="Foto Kandang" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg px-8 py-3 text-base shadow transition-all font-sans">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
