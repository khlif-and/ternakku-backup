@extends('layouts.care_livestock.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Pembelian Pakan / Obat ]</p>
        </div>

        <div id="livestock-list-container"
            class="bg-white rounded-2xl shadow-lg overflow-hidden min-h-[550px] w-full opacity-0 translate-y-4 transition-all duration-700">

            @include('admin.care_livestock.feed_medicine_purchase.partials.tabel')

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
            border-color:
                box-shadow: 0 0 0 2px
        }
    </style>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#livestock-list-container').removeClass('opacity-0 translate-y-4')
                    .addClass('opacity-100 translate-y-0');
            }, 100);
        });
    </script>
@endsection
