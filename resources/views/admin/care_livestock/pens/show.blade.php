@extends('layouts.care_livestock.index')

@section('content')
    <div class="max-w-4xl mx-auto py-10 font-sans">
        <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col md:flex-row items-center md:justify-between gap-6">

            <div class="flex items-center gap-4 w-full md:w-auto">
                @if ($pen->photo)
                    <img src="{{ $pen->photo }}" class="h-16 w-16 rounded-full object-cover flex-shrink-0"
                        alt="Foto Kandang {{ $pen->name }}">
                @else
                    <div class="h-16 w-16 rounded-full bg-slate-200 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http:
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif

                <div>
                    <h2 class="text-lg font-bold text-slate-800">{{ $pen->name }}</h2>
                    <p class="text-sm text-slate-500">Dari: {{ $farm->name }}</p>
                </div>
            </div>

            <hr class="w-full block md:hidden border-slate-200">

            <div class="flex-shrink-0 flex gap-8 text-center md:border-l md:pl-6">
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ $pen->capacity ?? '-' }}</p>
                    <p class="text-xs font-medium text-slate-500">Kapasitas</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">{{ $pen->population ?? 0 }}</p>
                    <p class="text-xs font-medium text-slate-500">Populasi</p>
                </div>
                <div>
                    @php
                        $percentage = $pen->capacity > 0 ? ($pen->population / $pen->capacity) * 100 : 0;
                    @endphp
                    <p class="text-2xl font-bold text-slate-900">{{ round($percentage) }}<span
                            class="text-xl text-slate-500">%</span></p>
                    <p class="text-xs font-medium text-slate-500">Kepadatan</p>
                </div>
            </div>

            <hr class="w-full block md:hidden border-slate-200">

            <div class="flex gap-3 w-full md:w-auto md:border-l md:pl-6">
                <a href="{{ route('admin.care-livestock.pens.index', $farm->id) }}"
                    class="w-full md:w-auto text-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-lg shadow-indigo-600/20">
                    Kembali
                </a>
            </div>

        </div>
    </div>
@endsection
