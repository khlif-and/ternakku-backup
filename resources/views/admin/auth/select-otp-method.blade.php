@extends('layouts.auth.index')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white">
    <div class="max-w-xl w-full grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
        <div class="bg-white border border-gray-300 rounded-lg shadow-md hover:shadow-lg cursor-pointer transition duration-200"
             onclick="location.href='{{ url('auth/otp-method/phone') }}'">
            <div class="p-6 text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Verifikasi via SMS</h3>
                <p class="text-gray-500">Kirim kode OTP ke nomor handphone Anda.</p>
            </div>
        </div>
        <div class="bg-white border border-gray-300 rounded-lg shadow-md hover:shadow-lg cursor-pointer transition duration-200"
             onclick="location.href='{{ url('auth/otp-method/email') }}'">
            <div class="p-6 text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Verifikasi via Email</h3>
                <p class="text-gray-500">Kirim kode OTP ke alamat email Anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection
