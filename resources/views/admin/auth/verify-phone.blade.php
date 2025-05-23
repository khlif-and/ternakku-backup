@extends('layouts.auth.index')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f9fafb] px-4">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl px-8 py-10 space-y-8">

        <div class="space-y-1">
            <h1 class="text-3xl font-bold text-gray-900">Verifikasi OTP</h1>
            <p class="text-sm text-gray-600 leading-relaxed">
                Kode OTP telah dikirim ke nomor <span class="font-semibold">{{ request('phone', 'nomor Anda') }}</span>.
                Masukkan kode 6 digit di bawah untuk melanjutkan.
            </p>
        </div>

        @if (session('success'))
            <div class="text-green-600 text-sm font-medium" id="otp-success-msg">
                {{ session('success') }} Mohon cek ya.
            </div>
        @endif

        <form method="POST" action="{{ url('auth/verify-otp') }}" id="otp-form" class="space-y-6">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', request('email')) }}">
            <input type="hidden" name="otp" id="otp">

            <div class="flex justify-between gap-2">
                @for ($i = 0; $i < 6; $i++)
                    <input type="password" maxlength="1" inputmode="numeric"
                        class="otp-box w-12 h-14 border border-gray-300 rounded-xl text-center text-2xl font-semibold focus:outline-none focus:ring-2 focus:ring-[#2d6a4f] transition duration-200"
                        oninput="handleInput(this, {{ $i }})"
                        onkeydown="handleBackspace(event, {{ $i }})">
                @endfor
            </div>

            <div class="space-y-1">
                @error('otp')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
                @error('email')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-[#2d6a4f] hover:bg-[#1b4634] text-white font-semibold py-3 rounded-lg shadow-md transition duration-150">
                Verifikasi Kode
            </button>

            @if (session('success'))
                <div id="countdown" class="text-sm text-gray-500 text-center">
                    Kode OTP berlaku selama <span id="timer">05:00</span>
                </div>
            @endif
        </form>

        <div class="text-sm text-gray-500" id="resend-wrapper" @if (session('success')) style="display: none;" @endif>
            Belum menerima kode?
            <form method="POST" action="/auth/resend-otp" class="inline">
                @csrf
                <input type="hidden" name="email" value="{{ request('email') }}">
                <button type="submit" class="text-[#2d6a4f] font-medium hover:underline ml-1">
                    Kirim ulang kode
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    const otpBoxes = document.querySelectorAll('.otp-box');
    const hiddenOtpInput = document.getElementById('otp');
    otpBoxes[0]?.focus();

    function handleInput(el, index) {
        const val = el.value.replace(/[^0-9]/g, '').slice(-1);

        if (val) {
            el.dataset.value = val;
            el.value = '•';
            if (index < otpBoxes.length - 1) {
                otpBoxes[index + 1].focus();
            }
        } else {
            el.dataset.value = '';
            el.value = '';
        }

        collectOtp();
    }

    function handleBackspace(event, index) {
        if (event.key === 'Backspace') {
            otpBoxes[index].value = '';
            otpBoxes[index].dataset.value = '';
            collectOtp();
            if (index > 0) {
                otpBoxes[index - 1].focus();
            }
        }
    }

    function collectOtp() {
        let otp = '';
        otpBoxes.forEach(box => {
            otp += box.dataset.value || '';
        });
        hiddenOtpInput.value = otp;
    }
</script>


<style>
    .otp-box {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        font-family: monospace;
        background-color: white;
    }

    .otp-box:focus {
        border-color: #2d6a4f;
        box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.2);
        background-color: #f0fdf4;
    }

    .otp-box::-ms-reveal,
    .otp-box::-ms-clear {
        display: none;
    }
</style>
@endsection
