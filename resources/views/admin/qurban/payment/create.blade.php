@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Data Payment ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Aktifitas</li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Data Payment</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ url('qurban/payment') }}"
                   class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-xl px-5 py-2 text-base shadow text-right transition-all font-sans">
                    kembali ke
                </a>
            </div>

            <div class="px-16 py-8">
                {{-- Pesan error/sukses --}}
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

                <form action="{{ url('qurban/payment') }}" method="POST" enctype="multipart/form-data" autocomplete="off" class="w-full max-w-full">
                    @csrf

                    <div class="mb-8">
                        <label for="pelanggan" class="block mb-2 text-base font-semibold text-gray-700">Pelanggan</label>
                        <select
                            id="pelanggan"
                            name="pelanggan"
                            required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Pilih Pelanggan</option>
                            <option value="1">Pelanggan 1</option>
                            <option value="2">Pelanggan 2</option>
                            <option value="3">Pelanggan 3</option>
                        </select>
                        @error('pelanggan')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label for="ternak" class="block mb-2 text-base font-semibold text-gray-700">Ternak</label>
                        <select
                            id="ternak"
                            name="ternak"
                            required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Pilih Ternak</option>
                            <option value="1">Sapi 1</option>
                            <option value="2">Sapi 2</option>
                            <option value="3">Kambing 1</option>
                        </select>
                        @error('ternak')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label for="jumlah" class="block mb-2 text-base font-semibold text-gray-700">Jumlah</label>
                        <select
                            id="jumlah"
                            name="jumlah"
                            required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Pilih Jumlah</option>
                            <option value="1">1 Ekor</option>
                            <option value="2">2 Ekor</option>
                            <option value="3">3 Ekor</option>
                        </select>
                        @error('jumlah')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-10">
                        {{-- Kolom Tanggal --}}
                        <div>
                            <label for="tanggal-airdatepicker" class="block mb-2 text-base font-semibold text-gray-700">Tanggal</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input
                                    id="tanggal-airdatepicker"
                                    name="tanggal"
                                    type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 py-3"
                                    placeholder="Select date"
                                    autocomplete="off"
                                    required>
                            </div>
                            @error('tanggal')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Kolom Foto (placeholder saja untuk kesamaan struktur) --}}
                        <div>
                            <label for="photo" class="block mb-2 text-base font-semibold text-gray-700">Foto</label>
                            <div class="relative">
                                <input
                                    type="file"
                                    class="peer absolute inset-0 opacity-0 w-full h-full z-10 cursor-pointer"
                                    id="photo"
                                    name="photo"
                                    accept="image/*"
                                    onchange="previewWeightPhoto(event)">
                                <div class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus-within:ring-2 focus-within:ring-blue-300 focus-within:border-blue-500 block w-full px-4 py-3 flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7m-2-2H5a2 2 0 0 0-2 2zm2 6l3 3a2 2 0 0 0 2.8 0l3-3m-6 0V7m0 6V7" />
                                    </svg>
                                    <span id="photo-placeholder" class="text-gray-400 select-none">Pilih foto</span>
                                    <span id="file-chosen" class="ml-2 text-gray-700"></span>
                                </div>
                            </div>
                            @error('photo')
                                <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                            @enderror
                            <div id="photo-preview-container" class="mt-4">
                                <img id="photo-preview" src="#" alt="Preview Foto Payment" class="hidden w-[140px] h-auto rounded-lg border shadow" />
                            </div>
                        </div>
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
    <!-- AIR DATEPICKER CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.js"></script>
    <style>
        .air-datepicker {
            border-radius: 14px !important;
            font-family: inherit;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.10) !important;
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
    <script>
        const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        document.addEventListener('DOMContentLoaded', function () {
            new AirDatepicker('#tanggal-airdatepicker', {
                autoClose: true,
                position: 'top left',
                locale: {
                    days: hari,
                    daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                    months: bulan,
                    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    today: 'Hari ini',
                    clear: 'Clear',
                    dateFormat: 'dd/MM/yyyy',
                    timeFormat: 'HH:mm',
                    firstDay: 1
                },
                dateFormat: function(date) {
                    let dayName = hari[date.getDay()];
                    let day = date.getDate();
                    let monthName = bulan[date.getMonth()];
                    let year = date.getFullYear();
                    return `${dayName}, ${day} ${monthName} ${year}`;
                },
                onShow: function(inst, animationCompleted){
                    setTimeout(() => {
                        let sel = document.querySelector('.air-datepicker-cell.-year-.-selected-');
                        if(sel) sel.scrollIntoView({block: 'center'});
                    }, 50);
                }
            });
        });

        function previewWeightPhoto(event) {
            const input = event.target;
            const preview = document.getElementById('photo-preview');
            const fileChosen = document.getElementById('file-chosen');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
                fileChosen.textContent = input.files[0].name;
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
                fileChosen.textContent = '';
            }
        }
    </script>
@endsection
