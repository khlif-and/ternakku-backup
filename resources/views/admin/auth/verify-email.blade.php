@extends('layouts.auth.index')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white">
    <div class="max-w-md w-full bg-white p-8 shadow-md rounded-lg text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Verifikasi Email Diperlukan</h2>
        <p class="text-gray-600 mb-2">Kami telah mengirimkan tautan verifikasi ke alamat email Anda.</p>
        <p class="text-gray-500 mb-6">Silakan cek email Anda dan klik tautan verifikasi untuk melanjutkan.</p>
        <a href="{{ url('auth/login') }}" class="text-[#255F38] hover:underline font-medium">Kembali ke Login</a>
    </div>
</div>
@endsection
