@extends('layouts.auth.index')

@section('content')
<div class="min-h-screen flex justify-center bg-gray-50 px-4 py-12">
    <div class="w-full max-w-6xl space-y-8 animate-fade-in">

        <!-- Heading -->
        <div class="text-center space-y-1">
            <h2 class="text-3xl font-bold text-gray-800">🚜 Buat Peternakan Baru</h2>
            <p class="text-gray-500 text-sm">Lengkapi informasi & unggah gambar untuk mulai mengelola farm-mu.</p>
        </div>

        <!-- Card -->
        <div class="bg-white shadow-lg rounded-xl p-8 space-y-10">
            <form action="{{ route('farm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf

                <!-- Error Display -->
                @if($errors->any())
                    <div class="text-red-500">
                        <ul class="text-sm">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Upload Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">📷 Gambar Peternakan</h3>
                    <div class="flex justify-between gap-6">
                        <!-- Cover Photo -->
<!-- Cover Photo -->
<div class="flex-1 relative">
    <input type="file" id="cover_photo_input" accept="image/*" name="cover_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'create_preview')">
    <div class="group relative border-2 border-dotted border-gray-300 rounded-xl h-64 overflow-hidden hover:border-[#6CC3A0] transition">
        <img id="create_preview" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:opacity-100 transition" />
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center group-hover:bg-opacity-50 transition z-0">
            <div class="text-white font-semibold text-center space-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16l4-4 4 4m0 0l4-4 4 4M4 4h16" />
                </svg>
                <span>Upload Cover Peternakan</span>
            </div>
        </div>
    </div>
</div>


<!-- Logo -->
<div class="flex-1 relative">
    <input type="file" id="logo_input" accept="image/*" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'select_preview')">
    <div class="group relative border-2 border-dotted border-gray-300 rounded-xl h-64 overflow-hidden hover:border-[#6CC3A0] transition">
        <img id="select_preview" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:opacity-100 transition" />
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center group-hover:bg-opacity-50 transition z-0">
            <div class="text-white font-semibold text-center space-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16l4-4 4 4m0 0l4-4 4 4M4 4h16" />
                </svg>
                <span>Upload Logo Peternakan</span>
            </div>
        </div>
    </div>
</div>

                    </div>
                </div>

                <!-- Informasi Farm -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">📄 Informasi Peternakan</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Peternakan</label>
                            <input type="text" name="name" id="name" required class="mt-1 w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6CC3A0]">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6CC3A0]"></textarea>
                        </div>

                        <div>
                            <label for="address_line" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <input type="text" name="address_line" id="address_line" required class="mt-1 w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6CC3A0]">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                <input type="text" name="postal_code" id="postal_code" class="mt-1 w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6CC3A0]">
                            </div>

                            <div>
                                <label for="region_id" class="block text-sm font-medium text-gray-700">Wilayah / Region</label>
                                <select id="region_id" name="region_id" required class="select2-region mt-1 w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6CC3A0]"></select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="mt-1 w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6CC3A0]">
                            </div>

                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="mt-1 w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6CC3A0]">
                            </div>
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                            <input type="number" name="capacity" id="capacity" class="mt-1 w-full border rounded-md px-4 py-2 focus:ring-2 focus:ring-[#6CC3A0]">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="qurban_partner" id="qurban_partner" class="mr-2">
                            <label for="qurban_partner" class="text-sm text-gray-700">Qurban Partner</label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="text-right">
                    <button type="submit" class="bg-[#6CC3A0] hover:bg-[#58ae8c] text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition duration-200">
                        Simpan Peternakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS & CSS Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
function previewImage(input, previewId) {
    const file = input.files[0];
    console.log('📷 File selected:', file); // Pastikan ini muncul di browser console
    const preview = document.getElementById(previewId);
    if (file) {
        const reader = new FileReader();
        reader.onload = e => preview.src = e.target.result;
        reader.readAsDataURL(file);
    }
}



$(document).ready(function () {
    $('.select2-region').select2({
        placeholder: 'Cari Wilayah...',
        ajax: {
            url: 'https://feedmill.ternakku.com/api/data-master/region',
            dataType: 'json',
            delay: 300,
            data: params => ({
                name: params.term,
                page: params.page || 1,
                per_page: 20
            }),
processResults: (response) => {
    console.log('🔍 Region API Response:', response);
    const items = response.data?.data || [];
    return {
        results: items.map(item => ({
            id: item.id,
            text: item.name
        })),
        pagination: {
            more: !!response.data?.next_page_url
        }
    };
},

            cache: true
        },
        minimumInputLength: 2
    });
});
</script>
@endsection
