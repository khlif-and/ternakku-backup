@extends('layouts.qurban.index')

@section('content')
<div class="p-4 sm:p-6 bg-gray-50 min-h-screen">

    <div>
        <h3 class="font-bold text-2xl text-gray-800">Ternak Kurban Dashboard</h3>
        <p class="text-gray-500 mt-1">Ringkasan visual data qurban di peternakan Anda.</p>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg bg-green-100 p-4 text-sm font-semibold text-green-800 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 space-y-6 pb-6">
        @include('admin.qurban.partials.main_card')

        @include('admin.qurban.partials.trend_charts')

        @include('admin.qurban.partials.delivery_status')

        @include('admin.qurban.partials.recent_activity')
    </div>

</div>
@endsection
