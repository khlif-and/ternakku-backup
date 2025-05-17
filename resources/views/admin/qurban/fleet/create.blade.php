@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Data Armada ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Data Awal</li>
                <li><i class="icon-arrow-right"></i></li>
                <li><a href="{{ url('qurban/fleet') }}" class="hover:text-blue-600">Data Armada</a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Tambah Data Armada</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ url('qurban/fleet') }}"
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

                <form action="{{ url('qurban/fleet') }}" method="POST" enctype="multipart/form-data" autocomplete="off" class="w-full max-w-full">
                    @csrf
                    <div class="mb-8">
                        <label for="name" class="block mb-2 text-base font-semibold text-gray-700">Nama Armada</label>
                        <input type="text"
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-500 @enderror"
                            id="name" name="name" required
                            placeholder="Contoh: Truk Engkel, Pickup, atau nama armada lainnya">
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-8">
                        <label for="police_number" class="block mb-2 text-base font-semibold text-gray-700">Nomor Polisi</label>
                        <input type="text"
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300 @error('police_number') border-red-500 @enderror"
                            id="police_number" name="police_number" required
                            placeholder="Contoh: B 1234 XYZ">
                        @error('police_number')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-8">
                        <label for="photo" class="block mb-2 text-base font-semibold text-gray-700">Foto</label>
                        <label class="w-full">
                            <span class="sr-only">Pilih Foto</span>
                            <input
                                type="file"
                                class="block w-full text-base text-gray-700 border rounded-md px-4 py-3 file:mr-4 file:py-3 file:px-8 file:rounded-lg file:border-0 file:text-base file:font-semibold file:bg-green-400 file:text-white hover:file:bg-green-500 focus:ring-2 focus:ring-blue-300 @error('photo') border-red-500 @enderror"
                                id="photo" name="photo" accept="image/*" onchange="previewFleetPhoto(event)">
                        </label>
                        @error('photo')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                        {{-- Preview gambar --}}
                        <div id="photo-preview-container" class="mt-4">
                            <img id="photo-preview" src="#" alt="Preview Foto Armada" class="hidden w-[140px] h-auto rounded-lg border shadow" />
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
<script>
    function previewFleetPhoto(event) {
        const input = event.target;
        const preview = document.getElementById('photo-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
        }
    }
</script>
@endsection
