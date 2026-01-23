@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h3 class="font-bold text-2xl text-gray-800">Laporan Kandang</h3>
            <p class="text-gray-500 mt-1">Lihat laporan lengkap kandang beserta data ternak, pakan, pengobatan, dan produksi susu.</p>
        </div>

        @livewire('admin.pen-report.index-component', ['farm' => $farm])
    </div>
@endsection
