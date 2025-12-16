@extends('layouts.auth.index')

@section('title', 'Buat Peternakan Baru')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    /* styling select2 + z-index biar popup gak ketutup card */
    .select2-container--default .select2-selection--single{
      background-color:#f8fafc;border:1px solid #cbd5e1;border-radius:.5rem;height:46px
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
      color:#334155;line-height:44px;padding-left:1rem;font-size:.875rem
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{height:44px}
    .select2-container--default.select2-container--open .select2-selection--single{
      border-color:#10b981;box-shadow:0 0 0 1px #10b981
    }
    .select2-dropdown{border-color:#cbd5e1;border-radius:.5rem}
    .select2-search--dropdown .select2-search__field{border-color:#cbd5e1;border-radius:.5rem}
    .select2-container{z-index:50}
    .select2-dropdown{z-index:60}
  </style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-12 font-sans">
  <div class="mx-auto w-full max-w-7xl animate-fade-in space-y-8">

    <div class="text-center">
      <h2 class="text-3xl font-bold tracking-tight text-slate-800 sm:text-4xl">🚜 Buat Peternakan Baru</h2>
      <p class="mt-2 text-base text-slate-500">Lengkapi informasi & unggah gambar untuk mulai mengelola farm-mu.</p>
    </div>

    <form action="{{ route('farm.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-x-12 gap-y-10 lg:grid-cols-5">
      @csrf

      <div class="space-y-10 lg:col-span-2">
        <div>
          <h3 class="text-lg font-semibold text-slate-700">📷 Gambar Cover</h3>
          <p class="mt-1 text-sm text-slate-500">Rasio terbaik adalah 16:9.</p>
          <div class="group relative mt-4">
            <input type="file" id="cover_photo_input" accept="image/*" name="cover_photo"
                   class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                   onchange="previewImage(this, 'cover_preview')">
            <div class="flex h-64 w-full items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-100/80 text-center transition-all duration-300 group-hover:border-emerald-400 group-hover:bg-emerald-50">
              <img id="cover_preview" class="absolute inset-0 h-full w-full rounded-xl object-cover" src="" alt="Preview Cover" style="display:none;">
              <div id="cover_placeholder" class="text-slate-500">
                <svg class="mx-auto h-12 w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <p class="mt-2 font-semibold">Klik untuk mengunggah</p>
                <p class="text-xs">PNG, JPG, atau WEBP.</p>
              </div>
            </div>
          </div>
        </div>

        <div>
          <h3 class="text-lg font-semibold text-slate-700">🖼️ Logo Peternakan</h3>
          <p class="mt-1 text-sm text-slate-500">Gunakan rasio persegi 1:1.</p>
          <div class="group relative mt-4">
            <input type="file" id="logo_input" accept="image/*" name="logo"
                   class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0"
                   onchange="previewImage(this, 'logo_preview')">
            <div class="flex h-64 w-full items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-100/80 text-center transition-all duration-300 group-hover:border-emerald-400 group-hover:bg-emerald-50">
              <img id="logo_preview" class="absolute inset-0 h-full w-full rounded-xl object-cover" src="" alt="Preview Logo" style="display:none;">
              <div id="logo_placeholder" class="text-slate-500">
                <svg class="mx-auto h-12 w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.03 1.125 0 1.131.094 1.976 1.057 1.976 2.192V7.5M8.25 7.5h7.5m-7.5 0-1 9.75L8.25 21h7.5l.25-2.25-1-9.75m-7.5 0h7.5"/>
                </svg>
                <p class="mt-2 font-semibold">Klik untuk mengunggah</p>
                <p class="text-xs">Gunakan logo transparan.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full rounded-2xl bg-white p-8 shadow-xl lg:col-span-3">
        <div class="space-y-8">
          @if($errors->any())
            <div class="rounded-lg border-l-4 border-red-400 bg-red-50 p-4" role="alert">
              <h3 class="font-bold text-red-800">Oops! Ada kesalahan</h3>
              <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-slate-800 border-b pb-3">📄 Informasi Peternakan</h3>

            <div>
              <label for="name" class="block text-sm font-medium text-slate-700">Nama Peternakan</label>
              <input type="text" name="name" id="name" required placeholder="Contoh: TernakKu Jaya Farm"
                     class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
              <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
              <textarea name="description" id="description" rows="3" placeholder="Jelaskan sedikit tentang peternakan Anda"
                        class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"></textarea>
            </div>

            <div>
              <label for="address_line" class="block text-sm font-medium text-slate-700">Alamat Lengkap</label>
              <input type="text" name="address_line" id="address_line" required placeholder="Jl. Peternakan No. 1, Cihideung"
                     class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="postal_code" class="block text-sm font-medium text-slate-700">Kode Pos</label>
                <input type="text" name="postal_code" id="postal_code" placeholder="40559"
                       class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
              </div>
              <div>
                <label for="region_id" class="block text-sm font-medium text-slate-700">Wilayah / Region</label>
                <select id="region_id" name="region_id" required
                        class="select2-region mt-1 block w-full rounded-lg border-slate-300 bg-slate-50 py-3 px-4"></select>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="longitude" class="block text-sm font-medium text-slate-700">Longitude</label>
                <input type="text" name="longitude" id="longitude" placeholder="-6.8714"
                       class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
              </div>
              <div>
                <label for="latitude" class="block text-sm font-medium text-slate-700">Latitude</label>
                <input type="text" name="latitude" id="latitude" placeholder="107.5731"
                       class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
              </div>
            </div>

            <div>
              <label for="capacity" class="block text-sm font-medium text-slate-700">Kapasitas (Ekor)</label>
              <input type="number" name="capacity" id="capacity" placeholder="500"
                     class="mt-1 block w-full rounded-lg border-slate-300 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="flex items-center pt-2">
              <input type="checkbox" name="qurban_partner" id="qurban_partner" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
              <label for="qurban_partner" class="ml-3 block text-sm font-medium text-slate-700">Daftarkan sebagai Mitra Kurban</label>
            </div>
          </div>

          <div class="pt-4 text-right">
            <button type="submit"
                    class="flex w-full items-center justify-center rounded-lg bg-emerald-600 px-8 py-3 text-base font-bold text-white transition-all duration-300 hover:bg-emerald-700 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
              <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.06 0l4-5.5Z" clip-rule="evenodd" />
              </svg>
              Simpan Peternakan
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
  {{-- Select2 JS (HARUS setelah jQuery dari layout) --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    // helper preview image
    function previewImage(input, previewId) {
      const file = input.files?.[0];
      const preview = document.getElementById(previewId);
      const placeholder = document.getElementById(previewId.replace('_preview', '_placeholder'));
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
      };
      reader.readAsDataURL(file);
    }

    $(function () {
      // sanity check: select2 must be loaded
      if (typeof $.fn.select2 !== 'function') {
        console.error('Select2 belum ke-load. Cek urutan script.');
        return;
      }

      const $el = $('.select2-region');

      // NOTE: kalau API feedmill kena CORS, pakai reverse proxy Nginx (/ext/region)
      const REGION_URL = 'https://feedmill.ternakku.com/api/data-master/region';
      // const REGION_URL = '/ext/region'; // <- pakai ini kalau sudah set reverse proxy

      $el.select2({
        placeholder: 'Pilih wilayah…',
        width: '100%',
        minimumInputLength: 0,
        dropdownParent: $('#region_id').parent(),
        ajax: {
          url: REGION_URL,
          dataType: 'json',
          delay: 300,
          data: params => ({
            name: params.term || '',
            page: params.page || 1,
            per_page: 20
          }),
          processResults: (res) => {
            const p = res?.data || res;
            const items = p?.data || p?.items || [];
            const more  = !!(p?.next_page_url || p?.next);
            return {
              results: items.map(it => ({ id: it.id, text: it.name })),
              pagination: { more }
            };
          },
          cache: true
        },
        language: {
          inputTooShort: () => "Ketik untuk cari…",
          searching:     () => "🔍 Mencari…",
          noResults:     () => "Wilayah tidak ditemukan",
          errorLoading:  () => "Gagal memuat hasil"
        }
      });

      // preload old value kalau ada
      const oldId = @json(old('region_id'));
      const oldText = @json(old('region_name')); // isi kalau kamu simpan label sebelumnya
      if (oldId && oldText) {
        const opt = new Option(oldText, oldId, true, true);
        $el.append(opt).trigger('change');
      }
    });
  </script>
@endpush
