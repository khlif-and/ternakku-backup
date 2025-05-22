@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Tambah Surat Jalan Qurban ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Aktivitas</li>
                <li><i class="icon-arrow-right"></i></li>
                <li><a href="{{ url('qurban/qurban-delivery-order-data') }}" class="hover:text-blue-600">Surat Jalan Qurban</a></li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ url('qurban/qurban-delivery-order-data') }}"
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

                <form action="{{ url('qurban/qurban-delivery-order-data') }}" method="POST" enctype="multipart/form-data" autocomplete="off" class="w-full max-w-full">
                    @csrf

                    {{-- Nomor Transaksi --}}
                    <div class="mb-8">
                        <label for="transaction_number" class="block mb-2 text-base font-semibold text-gray-700">Nomor Transaksi</label>
                        <input type="text" name="transaction_number" id="transaction_number" class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" required>
                    </div>

                    {{-- Tanggal Transaksi --}}
                    <div class="mb-8">
                        <label for="transaction_date" class="block mb-2 text-base font-semibold text-gray-700">Tanggal Transaksi</label>
                        <input type="text" id="tanggal-airdatepicker" name="transaction_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 py-3" placeholder="Pilih tanggal" required>
                    </div>

                    {{-- Unggah Foto --}}
                    <div class="mb-8">
                        <label for="photo" class="block mb-2 text-base font-semibold text-gray-700">Unggah Foto</label>
                        <input type="file" name="photo" id="photo" accept="image/*" class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" onchange="previewWeightPhoto(event)">
                        <div class="mt-2">
                            <span id="file-chosen" class="text-sm text-gray-500"></span>
                            <img id="photo-preview" class="hidden mt-2 max-h-40 rounded" />
                        </div>
                    </div>

                    {{-- Jadwal Pengiriman --}}
                    <div class="mb-8">
                        <label for="delivery_schedule" class="block mb-2 text-base font-semibold text-gray-700">Jadwal Pengiriman</label>
                        <input type="text" name="delivery_schedule" class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" required>
                    </div>

                    {{-- Farm --}}
                    <div class="mb-8">
                        <label for="farm" class="block mb-2 text-base font-semibold text-gray-700">Farm</label>
                        <select id="farm" name="farm" class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" onchange="toggleFarmDetails(this.value)" required>
                            <option value="">Pilih Farm</option>
                            <option value="farm1">Farm A</option>
                            <option value="farm2">Farm B</option>
                        </select>
                    </div>

                    <div id="farm-details" class="mb-8 space-y-4 hidden">
                        <div><label class="block mb-1 text-gray-700 font-medium">Tanggal Pendaftaran</label><input type="date" name="farm_registered_date" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Nomor Wilayah</label><input type="text" name="farm_region_number" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Nama Wilayah</label><input type="text" name="farm_region_name" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Kode Pos</label><input type="text" name="farm_postal_code" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Alamat Farm</label><textarea name="farm_address" class="w-full px-4 py-3 border rounded-md"></textarea></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Longitude</label><input type="text" name="farm_longitude" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Latitude</label><input type="text" name="farm_latitude" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Kapasitas</label><input type="number" name="farm_capacity" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Deskripsi</label><textarea name="farm_description" class="w-full px-4 py-3 border rounded-md"></textarea></div>
                    </div>

                    {{-- Customer --}}
                    <div class="mb-8">
                        <label for="customer" class="block mb-2 text-base font-semibold text-gray-700">Customer</label>
                        <select id="customer" name="customer" class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" onchange="toggleCustomerDetails(this.value)" required>
                            <option value="">Pilih Customer</option>
                            <option value="cust1" data-name="Customer A" data-phone="0812345678" data-email="custA@mail.com" data-address="Jl. Mawar No. 1">Customer A</option>
                            <option value="cust2" data-name="Customer B" data-phone="0823456789" data-email="custB@mail.com" data-address="Jl. Melati No. 2">Customer B</option>
                        </select>
                    </div>

                    <div id="customer-details" class="mb-8 space-y-4 hidden">
                        <div><label class="block mb-1 text-gray-700 font-medium">Nama</label><input type="text" name="customer_name" id="customer_name" class="w-full px-4 py-3 border rounded-md bg-gray-100" readonly></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Nomor Telepon</label><input type="text" name="customer_phone" id="customer_phone" class="w-full px-4 py-3 border rounded-md bg-gray-100" readonly></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Email</label><input type="email" name="customer_email" id="customer_email" class="w-full px-4 py-3 border rounded-md bg-gray-100" readonly></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Alamat</label><textarea name="customer_full_address" id="customer_full_address" class="w-full px-4 py-3 border rounded-md bg-gray-100" readonly></textarea></div>
                    </div>

                    {{-- Alamat Customer (trigger detail input) --}}
                    <div class="mb-8">
                        <label for="customer_address" class="block mb-2 text-base font-semibold text-gray-700">Alamat Customer</label>
                        <textarea name="customer_address" rows="3" class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" oninput="toggleAddressDetails(this.value)" required></textarea>
                    </div>

                    <div id="address-details" class="mb-8 space-y-4 hidden">
                        <div><label class="block mb-1 text-gray-700 font-medium">Nama</label><input type="text" name="address_name" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Deskripsi Alamat</label><textarea name="address_description" class="w-full px-4 py-3 border rounded-md"></textarea></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Nomor Wilayah</label><input type="text" name="address_region_number" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Nama Wilayah</label><input type="text" name="address_region_name" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Kode Pos</label><input type="text" name="address_postal_code" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Nama Jalan</label><input type="text" name="address_street" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Longitude</label><input type="text" name="address_longitude" class="w-full px-4 py-3 border rounded-md"></div>
                        <div><label class="block mb-1 text-gray-700 font-medium">Latitude</label><input type="text" name="address_latitude" class="w-full px-4 py-3 border rounded-md"></div>
                    </div>

                    {{-- Detail --}}
                    <div class="mb-8">
                        <label for="detail" class="block mb-2 text-base font-semibold text-gray-700">Detail</label>
                        <select name="detail" id="detail" class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" onchange="toggleLivestockDetail(this.value)" required>
                            <option value="">Pilih Detail</option>
                            <option value="1">Kerbau - EARTAG09</option>
                            <option value="2">Sapi - EARTAG77</option>
                        </select>
                    </div>

                    <div id="livestock-detail" class="mb-8 space-y-4 hidden">
                        <div><label class="block text-sm font-medium">Farm</label><input type="text" class="w-full px-4 py-2 border rounded" value="CV. Silih Wangi Sawargi" readonly></div>
                        <div><label class="block text-sm font-medium">Eartag</label><input type="text" class="w-full px-4 py-2 border rounded" value="EARTAG09" readonly></div>
                        <div><label class="block text-sm font-medium">RFID</label><input type="text" class="w-full px-4 py-2 border rounded" value="RFID5" readonly></div>
                        <div><label class="block text-sm font-medium">Tanggal Terima</label><input type="text" class="w-full px-4 py-2 border rounded" value="2024-08-18" readonly></div>
                        <div><label class="block text-sm font-medium">DOF</label><input type="text" class="w-full px-4 py-2 border rounded" value="272" readonly></div>
                        <div><label class="block text-sm font-medium">Berat Terima</label><input type="text" class="w-full px-4 py-2 border rounded" value="250.00 kg" readonly></div>
                        <div><label class="block text-sm font-medium">Berat Sekarang</label><input type="text" class="w-full px-4 py-2 border rounded" value="250.00 kg" readonly></div>
                        <div><label class="block text-sm font-medium">Umur Sekarang</label><input type="text" class="w-full px-4 py-2 border rounded" value="3 years and 8 months" readonly></div>
                        <div><label class="block text-sm font-medium">Jenis Ternak</label><input type="text" class="w-full px-4 py-2 border rounded" value="kerbau" readonly></div>
                        <div><label class="block text-sm font-medium">Grup</label><input type="text" class="w-full px-4 py-2 border rounded" value="pedaging" readonly></div>
                        <div><label class="block text-sm font-medium">Ras</label><input type="text" class="w-full px-4 py-2 border rounded" value="Kalang" readonly></div>
                        <div><label class="block text-sm font-medium">Jenis Kelamin</label><input type="text" class="w-full px-4 py-2 border rounded" value="betina" readonly></div>
                        <div><label class="block text-sm font-medium">Kandang</label><input type="text" class="w-full px-4 py-2 border rounded" value="quia Pen" readonly></div>
                        <div><label class="block text-sm font-medium">Harga Terima</label><input type="text" class="w-full px-4 py-2 border rounded" value="Rp 12.500.000" readonly></div>
                    </div>s

                    {{-- Livestock --}}
 <div class="mb-8">
                        <label for="livestock" class="block mb-2 text-base font-semibold text-gray-700">Livestock</label>
                        <select name="livestock" id="livestock" class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300" onchange="toggleLivestock(this.value)" required>
                            <option value="">Pilih Livestock</option>
                            <option value="1">EARTAG175 - Kerbau - CV. Silih Wangi Sawargi</option>
                            <option value="2">EARTAG176 - Sapi - Farm B</option>
                        </select>
                    </div>

                    <div id="livestock-info" class="mb-8 space-y-4 hidden">
                        <div><label class="block text-sm font-medium">Farm</label><input type="text" class="w-full px-4 py-2 border rounded" value="CV. Silih Wangi Sawargi" readonly></div>
                        <div><label class="block text-sm font-medium">Eartag</label><input type="text" class="w-full px-4 py-2 border rounded" value="EARTAG175" readonly></div>
                        <div><label class="block text-sm font-medium">RFID</label><input type="text" class="w-full px-4 py-2 border rounded" value="RFID454" readonly></div>
                        <div><label class="block text-sm font-medium">Tanggal Terima</label><input type="text" class="w-full px-4 py-2 border rounded" value="2024-08-26" readonly></div>
                        <div><label class="block text-sm font-medium">DOF</label><input type="text" class="w-full px-4 py-2 border rounded" value="264" readonly></div>
                        <div><label class="block text-sm font-medium">Berat Terima</label><input type="text" class="w-full px-4 py-2 border rounded" value="229.25 kg" readonly></div>
                        <div><label class="block text-sm font-medium">Berat Sekarang</label><input type="text" class="w-full px-4 py-2 border rounded" value="229.25 kg" readonly></div>
                        <div><label class="block text-sm font-medium">Umur Sekarang</label><input type="text" class="w-full px-4 py-2 border rounded" value="3 years and 3 months" readonly></div>
                        <div><label class="block text-sm font-medium">Jenis Ternak</label><input type="text" class="w-full px-4 py-2 border rounded" value="kerbau" readonly></div>
                        <div><label class="block text-sm font-medium">Grup</label><input type="text" class="w-full px-4 py-2 border rounded" value="pedaging" readonly></div>
                        <div><label class="block text-sm font-medium">Ras</label><input type="text" class="w-full px-4 py-2 border rounded" value="Murrah" readonly></div>
                        <div><label class="block text-sm font-medium">Jenis Kelamin</label><input type="text" class="w-full px-4 py-2 border rounded" value="jantan" readonly></div>
                        <div><label class="block text-sm font-medium">Kandang</label><input type="text" class="w-full px-4 py-2 border rounded" value="et Pen" readonly></div>
                        <div><label class="block text-sm font-medium">Harga Terima</label><input type="text" class="w-full px-4 py-2 border rounded" value="Rp 1.208,46" readonly></div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit"
                                class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-lg px-8 py-3 text-base shadow transition-all font-sans">
                            Simpan Surat Jalan
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
    </style>
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

        function toggleFarmDetails(value) {
            const detailDiv = document.getElementById('farm-details');
            if (value) {
                detailDiv.classList.remove('hidden');
            } else {
                detailDiv.classList.add('hidden');
            }
        }

        function toggleCustomerDetails(value) {
            const select = document.getElementById('customer');
            const option = select.options[select.selectedIndex];
            if (!value || !option.dataset.name) {
                document.getElementById('customer-details').classList.add('hidden');
                return;
            }
            document.getElementById('customer_name').value = option.dataset.name;
            document.getElementById('customer_phone').value = option.dataset.phone;
            document.getElementById('customer_email').value = option.dataset.email;
            document.getElementById('customer_full_address').value = option.dataset.address;
            document.getElementById('customer-details').classList.remove('hidden');
        }

        function toggleAddressDetails(value) {
            const detailDiv = document.getElementById('address-details');
            if (value.trim() !== '') {
                detailDiv.classList.remove('hidden');
            } else {
                detailDiv.classList.add('hidden');
            }
        }

        function toggleLivestockDetail(value) {
            const section = document.getElementById('livestock-detail');
            section.classList.toggle('hidden', !value);
        }

        function toggleLivestock(value) {
            const section = document.getElementById('livestock-info');
            if (value && value.trim() !== '') {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        }

    </script>
@endsection
