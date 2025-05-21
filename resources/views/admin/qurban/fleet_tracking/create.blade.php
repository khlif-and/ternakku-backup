@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Data Pelacakan Armada ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Aktivitas</li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Fleet Tracking</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ url('qurban/fleet-tracking') }}"
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

                <form action="{{ url('qurban/fleet-tracking') }}" method="POST" autocomplete="off" class="w-full max-w-full">
                    @csrf

                    <div class="mb-8">
                        <label for="pelanggan" class="block mb-2 text-base font-semibold text-gray-700">Pelanggan</label>
                        <select name="pelanggan" id="pelanggan" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Pilih Pelanggan</option>
                            <option value="1">Pelanggan 1</option>
                            <option value="2">Pelanggan 2</option>
                            <option value="3">Pelanggan 3</option>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label for="ternak" class="block mb-2 text-base font-semibold text-gray-700">Jenis Ternak</label>
                        <select name="ternak" id="ternak" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Pilih Ternak</option>
                            <option value="1">Sapi 1</option>
                            <option value="2">Sapi 2</option>
                            <option value="3">Kambing 1</option>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label for="jumlah" class="block mb-2 text-base font-semibold text-gray-700">Jumlah Ternak</label>
                        <select name="jumlah" id="jumlah" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Pilih Jumlah</option>
                            <option value="1">1 Ekor</option>
                            <option value="2">2 Ekor</option>
                            <option value="3">3 Ekor</option>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label for="tanggal-airdatepicker" class="block mb-2 text-base font-semibold text-gray-700">Tanggal Pengiriman</label>
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
                                required
                            >
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
    <script>
        const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni','Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        document.addEventListener('DOMContentLoaded', function () {
            new AirDatepicker('#tanggal-airdatepicker', {
                autoClose: true,
                position: 'top left',
                locale: {
                    days: hari,
                    daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                    months: bulan,
                    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun','Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
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
    </script>
@endsection
