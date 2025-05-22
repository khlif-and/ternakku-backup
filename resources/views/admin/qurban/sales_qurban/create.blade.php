@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Tambah Data Sales Qurban ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Aktivitas</li>
                <li><i class="icon-arrow-right"></i></li>
                <li><a href="{{ url('qurban/sales-qurban') }}" class="hover:text-blue-600">Sales Qurban</a></li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ url('qurban/sales-qurban') }}"
                    class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow text-right transition-all font-sans">
                    kembali ke
                </a>
            </div>

            <div class="px-16 py-8">
                @if (session('error'))
                    <div class="mb-6 px-4 py-3 rounded bg-red-100 border border-red-400 text-red-700 font-semibold">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="mb-6 px-4 py-3 rounded bg-green-100 border border-green-400 text-green-700 font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ url('qurban/sales-qurban') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off" class="w-full max-w-full">
                    @csrf

                    <div class="mb-8">
                        <label for="name" class="block mb-2 text-base font-semibold text-gray-700">Nama</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                    </div>

                    <div class="mb-8">
                        <label for="jenis_ternak" class="block mb-2 text-base font-semibold text-gray-700">Jenis Ternak</label>
                        <select id="jenis_ternak" name="jenis_ternak" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Pilih Jenis Ternak</option>
                            <option value="Sapi">Sapi</option>
                            <option value="Kambing">Kambing</option>
                            <option value="Domba">Domba</option>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label for="berat_min" class="block mb-2 text-base font-semibold text-gray-700">Berat Minimum (kg)</label>
                        <input type="number" step="0.01" id="berat_min" name="berat_min" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                    </div>

                    <div class="mb-8">
                        <label for="berat_max" class="block mb-2 text-base font-semibold text-gray-700">Berat Maksimum (kg)</label>
                        <input type="number" step="0.01" id="berat_max" name="berat_max" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                    </div>

                    <div class="mb-8">
                        <label for="harga_per_kg" class="block mb-2 text-base font-semibold text-gray-700">Harga per Kg (Rp)</label>
                        <input type="number" step="0.01" id="harga_per_kg" name="harga_per_kg" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-lg px-8 py-3 text-base shadow transition-all font-sans">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.js"></script>
    <style>
        .air-datepicker {
            border-radius: 14px !important;
            font-family: inherit;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10) !important;
            border: none;
            min-width: 310px;
            max-width: 350px;
        }
        .air-datepicker--years {
            padding: 10px 0;
            max-height: 260px;
            min-width: 180px;
            overflow-y: auto;
        }
        .air-datepicker-cell.-year- {
            font-size: 1.15rem;
            padding: 8px 0;
            border-radius: 8px;
            margin: 0 10px 2px 10px;
            text-align: center;
            cursor: pointer;
            transition: background 0.12s, color 0.12s;
        }
        .air-datepicker-cell.-year-.-selected-,
        .air-datepicker-cell.-year-:hover {
            background: #22c55e !important;
            color: #fff !important;
            font-weight: 700;
        }
    </style>
@endsection
