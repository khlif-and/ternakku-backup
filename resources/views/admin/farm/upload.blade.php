<div>
    <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">📷 Gambar Peternakan</h3>
    <div class="flex justify-between gap-6">
        @foreach (['create_image' => 'Upload Cover Buat Peternakan', 'select_image' => 'Upload Logo Buat Peternakan'] as $id => $label)
            <label for="{{ $id }}_input"
                class="group flex-1 relative cursor-pointer border-2 border-dotted border-gray-300 rounded-xl h-64 hover:border-[#6CC3A0] transition overflow-hidden">
                <input type="file" accept="image/*" id="{{ $id }}_input" name="{{ $id }}" class="hidden" onchange="previewImage(this, '{{ $id }}_preview')">
                <img id="{{ $id }}_preview" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:opacity-100 transition" />
                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center group-hover:bg-opacity-50 transition">
                    <div class="text-white font-semibold text-center space-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16l4-4 4 4m0 0l4-4 4 4M4 4h16" />
                        </svg>
                        <span>{{ $label }}</span>
                    </div>
                </div>
            </label>
        @endforeach
    </div>
</div>
