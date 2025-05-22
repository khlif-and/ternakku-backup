@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Tambah Data Order Penjualan ]</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ url('qurban/delivery') }}"
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

                <form action="{{ url('qurban/delivery') }}" method="POST" enctype="multipart/form-data" autocomplete="off" class="w-full max-w-full">
                    @csrf

                    {{-- Tanggal Order --}}
                    <div class="mb-8">
                        <label for="tanggal-airdatepicker" class="block mb-2 text-base font-semibold text-gray-700">Tanggal Order</label>
                        <input id="tanggal-airdatepicker" name="tanggal_order" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 py-3"
                            placeholder="Pilih tanggal" autocomplete="off" required>
                        @error('tanggal_order')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-8">
                        <label for="deskripsi" class="block mb-2 text-base font-semibold text-gray-700">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300"
                            required></textarea>
                        @error('deskripsi')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    {{-- Customer --}}
                    <div class="mb-8">
                        <label for="customer" class="block mb-2 text-base font-semibold text-gray-700">Customer</label>
                        <select id="customer" name="customer" required
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">Pilih Customer</option>
                            <option value="cust1" data-name="Muhammad Iqbal Mubarok" data-phone="6282116654124" data-email="mubarok.iqbal20@gmail.com">Muhammad Iqbal Mubarok</option>
                            <option value="cust2" data-name="Customer B" data-phone="081234567891" data-email="customerB@example.com">Customer B</option>
                        </select>
                        @error('customer')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    {{-- Customer Detail --}}
                    <div id="customer-details" class="mb-8 space-y-6 hidden">
                        <div>
                            <label class="block mb-2 text-base font-semibold text-gray-700">Nama</label>
                            <input type="text" id="cust-name" name="cust_name" readonly
                                class="w-full px-4 py-3 border rounded-md text-base bg-gray-100">
                        </div>
                        <div>
                            <label class="block mb-2 text-base font-semibold text-gray-700">Email</label>
                            <input type="email" id="cust-email" name="cust_email" readonly
                                class="w-full px-4 py-3 border rounded-md text-base bg-gray-100">
                        </div>
                        <div>
                            <label class="block mb-2 text-base font-semibold text-gray-700">No. Telepon</label>
                            <input type="text" id="cust-phone" name="cust_phone" readonly
                                class="w-full px-4 py-3 border rounded-md text-base bg-gray-100">
                        </div>
                    </div>

                    {{-- Tombol Tambah Detail --}}
                    <div class="mb-8">
                        <label class="block mb-2 text-base font-semibold text-gray-700">Detail</label>
                        <button type="button" onclick="addDetailRow()"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded shadow transition">
                            Tambah Detail
                        </button>
                    </div>

                    {{-- Kontainer Detail --}}
                    <div id="detail-container" class="space-y-6 mb-8">
                        {{-- Baris detail akan ditambahkan via JS --}}
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

    {{-- Template Detail Row --}}
    <template id="detail-row-template">
        <div class="grid md:grid-cols-3 gap-4 detail-row bg-gray-50 p-4 rounded-xl border border-gray-200 relative">
            <div>
                <label class="block mb-1 font-medium text-gray-700">Jumlah</label>
                <input type="number" name="details[][quantity]" class="w-full border rounded px-4 py-2" required>
            </div>
            <div>
                <label class="block mb-1 font-medium text-gray-700">Total Berat (kg)</label>
                <input type="number" name="details[][total_weight]" class="w-full border rounded px-4 py-2" required>
            </div>
            <div>
                <label class="block mb-1 font-medium text-gray-700">Jenis Ternak</label>
                <select name="details[][livestock_type_id]" class="w-full border rounded px-4 py-2" required>
                    <option value="">Pilih Ternak</option>
                    <option value="1">Sapi</option>
                    <option value="2">Kerbau</option>
                    <option value="3">Kambing</option>
                </select>
            </div>
            <button type="button" onclick="removeDetailRow(this)"
                class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm font-bold">
                Hapus
            </button>
        </div>
    </template>
@endsection

@section('script')
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
                }
            });

            document.getElementById('customer').addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const name = selected.getAttribute('data-name');
                const phone = selected.getAttribute('data-phone');
                const email = selected.getAttribute('data-email');

                const detailsDiv = document.getElementById('customer-details');
                if (name && phone && email) {
                    detailsDiv.classList.remove('hidden');
                    document.getElementById('cust-name').value = name;
                    document.getElementById('cust-phone').value = phone;
                    document.getElementById('cust-email').value = email;
                } else {
                    detailsDiv.classList.add('hidden');
                    document.getElementById('cust-name').value = '';
                    document.getElementById('cust-phone').value = '';
                    document.getElementById('cust-email').value = '';
                }
            });
        });

        function addDetailRow() {
            const container = document.getElementById('detail-container');
            const template = document.getElementById('detail-row-template');
            const clone = template.content.cloneNode(true);
            container.appendChild(clone);
        }

        function removeDetailRow(button) {
            const row = button.closest('.detail-row');
            if (row) row.remove();
        }
    </script>
@endsection
