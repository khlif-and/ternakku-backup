@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Daftar Kematian Ternak ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Care Livestock</li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Kematian Ternak</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full">
            <div class="px-8 py-6">
                @livewire('admin.livestock-death.index-component', ['farm' => $farm])
            </div>
        </div>
    </div>
@endsection
