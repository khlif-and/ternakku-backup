@php
    $is = fn(...$patterns) => request()->is(...$patterns);
    $notReport = fn(...$patterns) => $is(...$patterns) && !$is('care-livestock/*/report*');

    $reportSupplyPatterns = [
        'care-livestock/*/report/feeding-colony-supply*',
        'care-livestock/*/report/feeding-individu-supply*',
        'care-livestock/*/report/treatment-colony*',
        'care-livestock/*/report/treatment-individu*',
        'care-livestock/*/report/milk-production-global*',
        'care-livestock/*/report/milk-production-individu*',
        'care-livestock/*/report/milk-analysis-global*',
        'care-livestock/*/report/milk-analysis-individu*',
        'care-livestock/*/report/sales-livestock*',
    ];

    $menuStates = json_encode([
        'sidebarCollapsed' => false,
        'dataAwalOpen' => $is('care-livestock/*/pens*', 'care-livestock/*/livestock-reception*', 'care-livestock/*/livestock-sale-weight*', 'care-livestock/*/livestock-death*', 'care-livestock/*/farm-users*', 'care-livestock/*/dashboard*'),
        'persediaanOpen' => $notReport('care-livestock/*/feeding*', 'care-livestock/*/treatment*', 'care-livestock/*/milk*', 'care-livestock/*/sales-livestock*'),
        'aktivitasOpen' => $notReport('care-livestock/*/feed-medicine*', 'care-livestock/*/mutation*', 'care-livestock/*/artificial*', 'care-livestock/*/natural*', 'care-livestock/*/pregnant*', 'care-livestock/*/birth*', 'care-livestock/*/sales-order*', 'care-livestock/*/customer*'),
        'laporanAktivitasOpen' => $is('care-livestock/*/report*') && !$is(...$reportSupplyPatterns),
        'laporanPersediaanOpen' => $is(...$reportSupplyPatterns),
        'pelengkapInnerOpen' => false,
        'pakanInnerOpen' => $is('care-livestock/*/feeding*'),
        'perawatanInnerOpen' => $is('care-livestock/*/treatment*'),
        'produksiInnerOpen' => $is('care-livestock/*/milk-production*'),
        'analisisInnerOpen' => $is('care-livestock/*/milk-analysis*'),
        'inseminasiInnerOpen' => $is('care-livestock/*/artificial*', 'care-livestock/*/natural*'),
    ]);
@endphp

<x-sidebar.wrapper :menuStates="$menuStates">
    <x-sidebar.header :farmName="$farm->name ?? 'Your Farm'" :farmDashboardUrl="route('admin.care-livestock.dashboard', ['farm_id' => $farm->id])" />

    <div class="border-t border-white/20 mx-4 my-3"></div>

    <nav class="px-2 text-sm">
        <ul class="space-y-5">
            @include('layouts.care_livestock.part._data_awal')
            @include('layouts.care_livestock.part._persediaan')
            @include('layouts.care_livestock.part._aktivitas')
            @include('layouts.care_livestock.part._laporan_aktivitas')
            @include('layouts.care_livestock.part._laporan_persediaan')
        </ul>
    </nav>
    <x-sidebar.toggle-button />
</x-sidebar.wrapper>