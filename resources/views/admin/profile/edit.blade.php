@extends('layouts.auth.index')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4" x-data="{ showPasswordForm: false }">
    <div class="max-w-2xl mx-auto space-y-8">

        <div class="text-center">
            <h1 class="text-3xl font-bold text-slate-800">Pengaturan Profil</h1>
            <p class="mt-2 text-slate-500">Kelola informasi akun Anda</p>
        </div>

        @if(session('success'))
            <div class="rounded-lg border-l-4 border-green-400 bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('password_success'))
            <div class="rounded-lg border-l-4 border-green-400 bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800">{{ session('password_success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800">Informasi Profil</h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui nama dan foto profil Anda</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <div class="flex items-center gap-6">
                    <div class="shrink-0">
                        @php
                            $photoUrl = null;
                            if ($user->profile && $user->profile->photo) {
                                $photoUrl = asset('storage/' . $user->profile->photo);
                            }
                        @endphp
                        <img id="preview-photo"
                             src="{{ $photoUrl ?? asset('admin/img/profile.jpg') }}"
                             class="h-20 w-20 rounded-full object-cover ring-4 ring-slate-100"
                             alt="Foto Profil">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Profil</label>
                        <input type="file" name="photo" accept="image/jpg,image/jpeg,image/png,image/gif"
                               onchange="document.getElementById('preview-photo').src = window.URL.createObjectURL(this.files[0])"
                               class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-slate-400">JPG, JPEG, PNG atau GIF. Maksimal 2MB.</p>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon</label>
                    <input type="text" value="{{ $user->phone_number }}" disabled
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500 cursor-not-allowed">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Ubah Password</h2>
                    <p class="text-sm text-slate-500 mt-1">Pastikan akun Anda menggunakan password yang kuat</p>
                </div>
                <button @click="showPasswordForm = !showPasswordForm" type="button"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                    <span x-text="showPasswordForm ? 'Tutup' : 'Ubah'"></span>
                </button>
            </div>

            <div x-show="showPasswordForm" x-transition x-cloak>
                <form action="{{ route('profile.change-password') }}" method="POST" class="p-8 space-y-6">
                    @csrf

                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-2">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                        <input type="password" id="new_password" name="new_password"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                               class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl shadow-lg shadow-slate-500/20 transition">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
