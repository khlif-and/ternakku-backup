@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Data Pengguna ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li><i class="icon-arrow-right"></i></li>
                <li><i class="icon-arrow-right"></i></li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ url('qurban/farm/user-list') }}"
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

                <form method="POST" action="{{ url('qurban/farm/add-user') }}" autocomplete="off" class="w-full max-w-full">
                    @csrf

                    {{-- Username Search --}}
                    <div class="mb-8">
                        <label class="block mb-2 text-base font-semibold text-gray-700" for="username">Username</label>
                        <input type="text" id="username" name="username"
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300 @error('username') border-red-500 @enderror"
                            placeholder="Masukkan Username..." required autocomplete="off">
                        @error('username')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                        <span id="username-notfound" class="text-red-500 text-xs hidden">Username tidak ditemukan.</span>
                    </div>

                    {{-- User info (hidden by default, smooth transition) --}}
                    <div id="user-info"
                        class="transition-all duration-500 overflow-hidden max-h-0 opacity-0 pointer-events-none">
                        <div class="mb-8">
                            <label class="block mb-2 text-base font-semibold text-gray-700" for="name">Nama</label>
                            <input type="text" id="name" name="name"
                                class="w-full px-4 py-3 border rounded-md text-base outline-none bg-gray-100"
                                readonly>
                        </div>
                        <div class="mb-8">
                            <label class="block mb-2 text-base font-semibold text-gray-700" for="email">Email</label>
                            <input type="text" id="email" name="email"
                                class="w-full px-4 py-3 border rounded-md text-base outline-none bg-gray-100"
                                readonly>
                        </div>
                        <div class="mb-8">
                            <label class="block mb-2 text-base font-semibold text-gray-700" for="phone_number">No HP</label>
                            <input type="text" id="phone_number" name="phone_number"
                                class="w-full px-4 py-3 border rounded-md text-base outline-none bg-gray-100"
                                readonly>
                        </div>
                        <input type="hidden" id="user_id" name="user_id" value="">
                        <div class="mb-8">
                            <label class="block mb-2 text-base font-semibold text-gray-700" for="farm_role">Role</label>
                            <select id="farm_role" name="farm_role"
                                class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300 @error('farm_role') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Role</option>
                                <option value="ADMIN">Admin</option>
                                <option value="ABK">ABK</option>
                                <option value="DRIVER">Driver</option>
                            </select>
                            @error('farm_role')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-lg px-8 py-3 text-base shadow transition-all font-sans">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var timer;
    $('#username').on('keyup change', function() {
        clearTimeout(timer);
        var username = $(this).val().trim();
        timer = setTimeout(function() {
            if (username.length > 0) {
                $.ajax({
                    url: '/qurban/farm/find-user',
                    type: 'get',
                    data: { username: username },
                    success: function(response) {
                        let data = response;
                        if (data && data.name) {
                            // Tampilkan user-info (smooth)
                            $('#user-info')
                                .removeClass('opacity-0 pointer-events-none max-h-0')
                                .addClass('opacity-100 max-h-[1000px]');
                            $('#name').val(data.name ?? '');
                            $('#email').val(data.email ?? '');
                            $('#phone_number').val(data.phone_number ?? '');
                            $('#user_id').val(data.id ?? '');
                            $('#username-notfound').addClass('hidden');
                        } else {
                            // Sembunyikan user-info (smooth)
                            $('#user-info')
                                .removeClass('opacity-100 max-h-[1000px]')
                                .addClass('opacity-0 pointer-events-none max-h-0');
                            $('#name, #email, #phone_number, #user_id').val('');
                            $('#username-notfound').removeClass('hidden');
                        }
                    },
                    error: function(xhr) {
                        $('#user-info')
                            .removeClass('opacity-100 max-h-[1000px]')
                            .addClass('opacity-0 pointer-events-none max-h-0');
                        $('#name, #email, #phone_number, #user_id').val('');
                        $('#username-notfound').removeClass('hidden');
                    }
                });
            } else {
                $('#user-info')
                    .removeClass('opacity-100 max-h-[1000px]')
                    .addClass('opacity-0 pointer-events-none max-h-0');
                $('#name, #email, #phone_number, #user_id').val('');
                $('#username-notfound').addClass('hidden');
            }
        }, 700);
    });
});
</script>
@endsection
