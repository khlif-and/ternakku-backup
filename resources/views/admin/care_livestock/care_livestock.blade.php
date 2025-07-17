@extends('layouts.care_livestock.index')

@section('content')
<div class="px-2 sm:px-4 md:px-8 py-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between pt-2 pb-4 gap-2">
        <div>
            <h3 class="font-bold text-2xl mb-3">Care Livestock Dashboard</h3>
            {{-- <h6 class="text-gray-400 mb-2">{{ $farm->name }}</h6> --}}
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between min-h-[170px]">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="font-bold text-base mb-0">Animal Stock</h6>
                    <p class="text-gray-500 text-sm">Currently Available</p>
                </div>
                <h4 class="text-indigo-500 font-extrabold text-2xl">145</h4>
            </div>
            <div class="w-full bg-gray-200 h-2 rounded mt-4">
                <div class="bg-indigo-400 h-2 rounded" style="width:60%"></div>
            </div>
            <div class="flex justify-end mt-2">
                <p class="text-gray-400 text-sm mb-0">60%</p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between min-h-[170px]">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="font-bold text-base mb-0">Feeding Activity</h6>
                    <p class="text-gray-500 text-sm">Today’s Completion</p>
                </div>
                <h4 class="text-emerald-500 font-extrabold text-2xl">32</h4>
            </div>
            <div class="w-full bg-gray-200 h-2 rounded mt-4">
                <div class="bg-emerald-400 h-2 rounded" style="width:40%"></div>
            </div>
            <div class="flex justify-end mt-2">
                <p class="text-gray-400 text-sm mb-0">40%</p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between min-h-[170px]">
            <div class="flex justify-between items-center">
                <div>
                    <h6 class="font-bold text-base mb-0">Health Checks</h6>
                    <p class="text-gray-500 text-sm">Weekly Progress</p>
                </div>
                <h4 class="text-rose-500 font-extrabold text-2xl">8</h4>
            </div>
            <div class="w-full bg-gray-200 h-2 rounded mt-4">
                <div class="bg-rose-400 h-2 rounded" style="width:20%"></div>
            </div>
            <div class="flex justify-end mt-2">
                <p class="text-gray-400 text-sm mb-0">20%</p>
            </div>
        </div>
    </div>
</div>
@endsection
