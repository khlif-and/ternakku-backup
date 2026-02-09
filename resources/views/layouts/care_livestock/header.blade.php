@php
    $currentFarm = \App\Models\Farm::find(session('selected_farm'));

    $userRole = \App\Models\FarmUser::where('user_id', auth()->id())
        ->where('farm_id', session('selected_farm'))
        ->value('farm_role');
    $isOwner = $userRole === 'OWNER' || ($currentFarm && $currentFarm->owner_id === auth()->id());
    $isAdmin = $userRole === 'ADMIN';
@endphp

<x-header.profile-dropdown :user="auth()->user()" :currentFarm="$currentFarm">
    <x-header.dropdown-item href="{{ url('dashboard') }}" label="Home / Dashboard" />
    <x-header.dropdown-item href="#" label="Profil Saya" />

    @if($isOwner || $isAdmin)
        <button @click="window.location.href='{{ url('select-farm') }}'"
            class="block w-full text-left px-3 py-2 text-sm hover:bg-slate-100 rounded">
            Ganti Kandang / Pen
        </button>
    @endif

    <button @click="logoutModal = true; open = false"
        class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded">
        Logout
    </button>
</x-header.profile-dropdown>