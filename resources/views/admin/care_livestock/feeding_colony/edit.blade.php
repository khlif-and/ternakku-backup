@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Edit Pemberian Pakan Koloni ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Care Livestock</li>
                <li><i class="icon-arrow-right"></i></li>
                <li><a href="{{ route('admin.care-livestock.feeding-colony.index', $farm->id) }}" class="hover:text-blue-600">Pemberian Pakan Koloni</a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Edit</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ route('admin.care-livestock.feeding-colony.index', $farm->id) }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow transition-all font-sans">
                    Kembali ke Daftar
                </a>
            </div>

            <div class="px-16 py-8">
                @livewire('admin.feeding-colony.edit-component', ['farm' => $farm, 'feedingColony' => $feedingColony])
            </div>
        </div>
    </div>
@endsection
