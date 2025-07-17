@extends('layouts.auth.index')

@section('content')
    <div class="w-screen h-screen grid grid-cols-1 md:grid-cols-5 bg-white overflow-hidden">
        <div class="col-span-5 md:col-span-3 flex flex-col justify-center px-10 md:px-20">
            <div class="mb-10">
                <span class="text-sm text-[#255F38] font-semibold">Ternakku</span>
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang Di Ternakku</h2>
            <p class="text-gray-500 mb-6">
                Silahkan, login terlebih dahulu untuk menikmati fitur Ternakku
            </p>

            {{-- ====== ALERTS ====== --}}
            @if (session('otp_unverified'))
                <div class="mb-4 text-sm font-semibold text-red-600">
                    {{ session('otp_unverified') }}
                </div>
            @endif

            @error('login_error')
                <div class="mb-4 text-sm font-semibold text-red-600">{{ $message }}</div>
            @enderror
            {{-- ==================== --}}

            <form method="POST" action="{{ url('auth/login') }}" class="space-y-4">
                @csrf

                <input type="email" name="username" placeholder="stanley@gmail.com"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#255F38] outline-none"
                       required value="{{ old('username') }}">

                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="********"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:ring-2 focus:ring-[#255F38] outline-none"
                           required>

                    <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.064 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember"
                               class="text-[#255F38] border-gray-300 rounded focus:ring-[#255F38]"
                               {{ old('remember') ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-[#255F38] hover:underline">Forgot Password?</a>
                </div>

                <button type="submit"
                        class="w-full bg-[#255F38] hover:bg-[#1d4c2d] text-white font-medium py-2 rounded-lg transition duration-200">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-sm text-center">
                <span class="text-gray-600">Don’t have an account?</span>
                <a href="{{ url('auth/register') }}" class="text-[#255F38] hover:underline font-medium">Sign Up</a>
            </div>
        </div>

        {{-- ==== Right Illustration ==== --}}
        <div class="hidden md:flex col-span-2 items-center justify-center p-6 overflow-hidden">
            <div class="bg-white rounded-3xl overflow-hidden
                        w-full max-w-[460px] aspect-[4/5]
                        xl:w-[230%] xl:h-[78vh] xl:max-w-none xl:aspect-auto">
                <img src="{{ asset('home/assets/img/auth_bg.png') }}" alt="Login Illustration"
                     class="w-full h-full object-cover rounded-2xl">
            </div>
        </div>
    </div>

    {{-- ===== Password toggle script ===== --}}
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField  = document.getElementById('password');
        const eyeIcon        = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', () => {
            const isHidden = passwordField.type === 'password';
            passwordField.type = isHidden ? 'text' : 'password';

            eyeIcon.innerHTML = isHidden ?
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                       d="M13.875 18.825A10.05 10.05 0 0112 19
                          c-4.477 0-8.268-2.943-9.542-7
                          a10.05 10.05 0 012.517-4.362m3.004-2.057
                          A9.965 9.965 0 0112 5
                          c4.478 0 8.268 2.943 9.542 7
                          a9.965 9.965 0 01-1.357 2.572M15 12a3 3 0 11-6 0
                          3 3 0 016 0z"/>
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                       d="M3 3l18 18"/>` :
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                       d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                       d="M2.458 12C3.732 7.943 7.523 5 12 5
                          c4.478 0 8.268 2.943 9.542 7
                          -1.274 4.057-5.064 7-9.542 7
                          -4.477 0-8.268-2.943-9.542-7z"/>`;
        });
    </script>
@endsection
