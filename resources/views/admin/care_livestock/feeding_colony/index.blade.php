@extends('layouts.care_livestock.index')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <p class="text-gray-700 text-lg mb-3 font-semibold">[ Pemberian Pakan Koloni ]</p>
    </div>

    <div id="feeding-colony-list-container"
         class="bg-white rounded-2xl shadow-lg overflow-hidden min-h-[550px] w-full opacity-0 translate-y-4 transition-all duration-700">

        {{-- Panggil partial tabel Feeding Colony di sini --}}
        @include('admin.care_livestock.feeding_colony.partials.tabel')
        {{-- Pastikan file partial bernama tabel.blade.php ada di folder resources/views/admin/care_livestock/feeding_colony/partials --}}

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
            $('#feeding-colony-list-container').removeClass('opacity-0 translate-y-4')
                .addClass('opacity-100 translate-y-0');
        }, 100);
    });
</script>
@endsection
