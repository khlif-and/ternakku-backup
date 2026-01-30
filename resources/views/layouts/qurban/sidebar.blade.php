@php
    $menuStates = '{
        sidebarCollapsed: false,
        dataAwalOpen: ' . (request()->routeIs('qurban.farm.*', 'qurban.customer.*', 'qurban.fleet.*', 'qurban.driver.*') ? 'true' : 'false') . ',
        aktivitasOpen: ' . (request()->routeIs('qurban.reweight.*', 'qurban.sales-order.*', 'qurban.payment.*', 'qurban.livestock-delivery-note.*', 'qurban.delivery.*', 'qurban.fleet-tracking.*', 'qurban.delivery-order-data.*', 'qurban.cancelation.*') || request()->is('qurban/sales-livestock') ? 'true' : 'false') . ',
        laporanOpen: ' . (request()->routeIs('qurban.population-report.*') || request()->is('qurban/report/*') ? 'true' : 'false') . '
    }';
@endphp

<x-sidebar.wrapper :menuStates="$menuStates">
    {{-- HEADER --}}
    <x-sidebar.header
        :farmName="$farm->name ?? 'Qurban Farm'"
        :farmDashboardUrl="route('qurban.dashboard')"
    />

    <div class="border-t border-white/20 mx-4 my-3"></div>

    <nav class="px-2 text-sm">
        <ul class="space-y-5">
            @include('layouts.qurban.part._data_awal')
            @include('layouts.qurban.part._aktivitas')
            @include('layouts.qurban.part._laporan')
        </ul>
    </nav>

    {{-- TOGGLE BUTTON --}}
    <x-sidebar.toggle-button />
</x-sidebar.wrapper>
