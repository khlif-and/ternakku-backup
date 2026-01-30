@php
    $currentFarm = null;
    if (session()->has('selected_farm')) {
        $currentFarm = \App\Models\Farm::find(session('selected_farm'));
    }
@endphp

<x-header.profile-dropdown :user="auth()->user()" :currentFarm="$currentFarm">
    <x-header.dropdown-item href="{{ route('qurban.dashboard') }}" label="Home / Dashboard" />
    <x-header.dropdown-item href="#" label="Profil Saya" />

    <button @click="window.location.href='{{ url('select-farm') }}'"
            class="block w-full text-left px-3 py-2 text-sm hover:bg-slate-100 rounded">
        Ganti Kandang / Pen
    </button>

    <button @click="logoutModal = true; open = false"
            class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded">
        Keluar
    </button>
</x-header.profile-dropdown>
