@php
    extract(App\Helpers\web\FarmRoleResolver::resolve());
@endphp

<x-header.profile-dropdown :user="auth()->user()" :currentFarm="$currentFarm">
    <x-header.dropdown-item href="{{ route('dashboard') }}" label="Home / Dashboard" />
    <x-header.dropdown-item :href="route('profile.edit')" label="Profil Saya" />

    @if($isOwner || $isAdmin)
        <button @click="window.location.href='{{ url('select-farm') }}'"
            class="block w-full text-left px-3 py-2 text-sm hover:bg-slate-100 rounded">
            Ganti Kandang / Pen
        </button>
    @endif

    <button @click="logoutModal = true; open = false"
        class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded">
        Keluar
    </button>
</x-header.profile-dropdown>