@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-gray-700 text-lg mb-1 font-semibold">[ Detail Kematian Ternak ]</p>
                <ul class="flex items-center text-sm space-x-2 text-gray-500">
                    <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                    <li><i class="icon-arrow-right"></i></li>
                    <li>Care Livestock</li>
                    <li><i class="icon-arrow-right"></i></li>
                    <li><a href="{{ route('admin.care-livestock.livestock-death.index', $farm->id) }}" class="hover:text-blue-600">Kematian Ternak</a></li>
                    <li><i class="icon-arrow-right"></i></li>
                    <li>Detail</li>
                </ul>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.care-livestock.livestock-death.edit', [$farm->id, $death->id]) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow transition-all">
                   Edit
                </a>
                <a href="{{ route('admin.care-livestock.livestock-death.index', $farm->id) }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-lg px-4 py-2 text-sm shadow transition-all">
                   Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full">
            <div class="px-8 py-6">
                @livewire('admin.livestock-death.show-component', ['farm' => $farm, 'death' => $death])
            </div>
        </div>
    </div>
@endsection
