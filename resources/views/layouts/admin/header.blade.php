@php
    $currentFarm = \App\Models\Farm::find(session('selected_farm'));
@endphp

<x-header.profile-dropdown :user="auth()->user()" :currentFarm="$currentFarm" :transparent="true">
    <x-header.dropdown-item href="#" label="Pengaturan Akun" />
    <x-header.dropdown-item href="{{ route('care_livestock') }}" label="Pelihara Ternak" />

    <button @click="logoutModal = true; open = false" type="button"
        class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded transition-colors">
        Logout
    </button>
</x-header.profile-dropdown>