@extends('layouts.auth.index')

@section('content')
<div class="w-screen h-screen grid grid-cols-1 md:grid-cols-5 bg-white overflow-hidden">
    <div class="col-span-5 md:col-span-3 flex flex-col justify-center px-10 md:px-20">
        <div class="mb-10">
            <span class="text-sm text-[#255F38] font-semibold">Ternakku</span>
        </div>

        <h2 class="text-3xl font-bold text-gray-900 mb-2">Daftar Akun Ternakku</h2>
        <p class="text-gray-500 mb-6">Silahkan, isi data diri untuk membuat akun baru di Ternakku</p>

        <form method="POST" action="{{ url('auth/register') }}" class="space-y-4">
            @csrf

            <input type="text" name="name" placeholder="Nama Lengkap"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#255F38] outline-none"
                required value="{{ old('name') }}">
            @error('name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

            <input type="email" name="email" placeholder="Email"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#255F38] outline-none"
                required value="{{ old('email') }}">
            @error('email') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

            <input type="text" name="phone_number" placeholder="Nomor HP (contoh: 6281234567890)"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#255F38] outline-none"
                required value="{{ old('phone_number') }}">
            @error('phone_number') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

            <div class="relative">
                <input id="passwordsignin" type="password" name="password" placeholder="Password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:ring-2 focus:ring-[#255F38] outline-none"
                    required>
                <button type="button" id="togglePasswordSignin"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                    <svg id="eyeIconSignin" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                            c4.478 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.064 7-9.542 7
                            -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            @error('password') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

            <div class="relative">
                <input id="confirmpassword" type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:ring-2 focus:ring-[#255F38] outline-none"
                    required>
                <button type="button" id="toggleConfirmPassword"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                    <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                            c4.478 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.064 7-9.542 7
                            -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <button type="submit"
                class="w-full bg-[#255F38] hover:bg-[#1d4c2d] text-white font-medium py-2 rounded-lg transition duration-200">
                Sign Up
            </button>
        </form>

        <div class="mt-6 text-sm text-center">
            <span class="text-gray-600">Sudah punya akun?</span>
            <a href="{{ url('auth/login') }}" class="text-[#255F38] hover:underline font-medium">Sign In</a>
        </div>
    </div>

    <div class="hidden md:flex col-span-2 items-center justify-center p-6 overflow-hidden">
        <div class="bg-white rounded-3xl overflow-hidden w-full max-w-[460px] aspect-[4/5] xl:w-[230%] xl:h-[78vh] xl:max-w-none xl:aspect-auto">
            <img src="{{ asset('home/assets/img/auth_bg.png') }}" alt="Register Illustration"
                class="w-full h-full object-cover rounded-2xl">
        </div>
    </div>
</div>

<script>
    const togglePasswordSignin = document.getElementById('togglePasswordSignin');
    const passwordSignin = document.getElementById('passwordsignin');

    togglePasswordSignin.addEventListener('click', () => {
        const type = passwordSignin.type === 'password' ? 'text' : 'password';
        passwordSignin.type = type;
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirmpassword');

    toggleConfirmPassword.addEventListener('click', () => {
        const type = confirmPassword.type === 'password' ? 'text' : 'password';
        confirmPassword.type = type;
    });
</script>
@endsection
