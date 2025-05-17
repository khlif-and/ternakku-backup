@extends('layouts.qurban.index')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <p class="text-gray-700 text-lg mb-3 font-semibold">[ Data Pelanggan ]</p>
            <ul class="flex items-center text-sm space-x-2 text-gray-500 mb-4">
                <li><a href="/" class="hover:text-blue-600"><i class="icon-home"></i></a></li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Data Awal</li>
                <li><i class="icon-arrow-right"></i></li>
                <li>Data Pelanggan</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full transition-all duration-300">
            <div class="w-full flex items-center justify-end px-8 h-[90px] bg-white border-b border-gray-200">
                <a href="{{ url('qurban/customer') }}"
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

                <form action="{{ url('qurban/customer') }}" method="POST" autocomplete="off" class="w-full max-w-full">
                    @csrf
                    <div class="mb-8">
                        <label for="name" class="block mb-2 text-base font-semibold text-gray-700">Nama</label>
                        <input type="text"
                            class="w-full px-4 py-3 border rounded-md text-base outline-none focus:ring-2 focus:ring-blue-300 @error('name') border-red-500 @enderror"
                            id="name" name="name" required>
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-8">
                        <label for="phone_number" class="block mb-2 text-base font-semibold text-gray-700">Nomor Telepon</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center space-x-1 select-none">
                                <!-- Bendera Bulat Indonesia SVG -->
                                <span class="inline-block w-6 h-6 rounded-full overflow-hidden ring-1 ring-gray-300 bg-white mr-1">
                                    <svg width="24" height="24" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="12" fill="#fff"/>
                                        <path d="M0 0h24v12H0z" fill="#E70011"/>
                                    </svg>
                                </span>
                                <span class="text-base font-semibold text-gray-800">+62</span>
                            </span>
                            <input
                                type="text"
                                class="pl-20 pr-4 py-3 border rounded-md w-full text-base outline-none focus:ring-2 focus:ring-blue-300 @error('phone_number') border-red-500 @enderror"
                                id="phone_number"
                                name="phone_number"
                                required
                                placeholder="812xxxxxxx">
                        </div>
                        @error('phone_number')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
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
@endsection
