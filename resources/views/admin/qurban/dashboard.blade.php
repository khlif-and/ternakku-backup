@extends('layouts.qurban.index')

@section('content')
<div class="px-2 sm:px-4 md:px-8 py-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between pt-2 pb-4 gap-2">
        <div>
            <h3 class="font-bold text-2xl mb-3">Ternak Kurban Dashboard</h3>
            {{-- <h6 class="text-gray-400 mb-2">{{ $farm->name }}</h6> --}}
        </div>
        <!--
        <div class="flex gap-2">
            <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
            <a href="#" class="btn btn-primary btn-round">Add Customer</a>
        </div>
        -->
    </div>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between min-h-[170px]">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="font-bold text-base mb-0">Sales Order</h6>
                    <p class="text-gray-500 text-sm">Completed</p>
                </div>
                <h4 class="text-sky-500 font-extrabold text-2xl">80</h4>
            </div>
            <div class="w-full bg-gray-200 h-2 rounded mt-4">
                <div class="bg-sky-400 h-2 rounded" style="width:75%"></div>
            </div>
            <div class="flex justify-end mt-2">
                <p class="text-gray-400 text-sm mb-0">75%</p>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between min-h-[170px]">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="font-bold text-base mb-0">Penjualan Ternak</h6>
                    <p class="text-gray-500 text-sm">Terjual</p>
                </div>
                <h4 class="text-green-500 font-extrabold text-2xl">120</h4>
            </div>
            <div class="w-full bg-gray-200 h-2 rounded mt-4">
                <div class="bg-green-400 h-2 rounded" style="width:25%"></div>
            </div>
            <div class="flex justify-end mt-2">
                <p class="text-gray-400 text-sm mb-0">25%</p>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between min-h-[170px]">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="font-bold text-base mb-0">Pengiriman</h6>
                    <p class="text-gray-500 text-sm">Terkirim</p>
                </div>
                <h4 class="text-red-500 font-extrabold text-2xl">15</h4>
            </div>
            <div class="w-full bg-gray-200 h-2 rounded mt-4">
                <div class="bg-red-400 h-2 rounded" style="width:50%"></div>
            </div>
            <div class="flex justify-end mt-2">
                <p class="text-gray-400 text-sm mb-0">50%</p>
            </div>
        </div>
    </div>
</div>
@endsection
