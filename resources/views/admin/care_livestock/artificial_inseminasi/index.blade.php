@extends('layouts.care_livestock.index')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <p class="text-gray-700 text-lg mb-3 font-semibold">[ Artificial Inseminasi ]</p>
    </div>

    <div id="artificial-inseminasi-list-container"
         class="bg-white rounded-2xl shadow-lg overflow-hidden min-h-[550px] w-full opacity-0 translate-y-4 transition-all duration-700">

        {{-- Livewire list --}}
        @livewire('admin.artificial-insemination.index-component', ['farm' => $farm])

        {{-- 🔹 Tambahkan ini --}}
        @if (session('error'))
            <div class="px-6 pt-4">
                <div class="px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                    {{ session('error') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


@section('script')
<style>
    .search-input-custom {
        border-color: rgba(0, 0, 0, 0.24) !important;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input-custom:focus {
        border-color: #28c76f !important;
        box-shadow: 0 0 0 2px #28c76f44;
    }
</style>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#artificial-inseminasi-list-container')
                .removeClass('opacity-0 translate-y-4')
                .addClass('opacity-100 translate-y-0');
        }, 100);
    });
</script>
@endsection
