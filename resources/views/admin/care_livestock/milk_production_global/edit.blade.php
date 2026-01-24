@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Produksi Susu Global" 
        :backUrl="route('admin.care-livestock.milk-production-global.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Produksi Susu Global', 'route' => route('admin.care-livestock.milk-production-global.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.milk-production-global.edit-component', [
            'farm' => $farm, 
            'milkProductionGlobal' => $milkProductionGlobal
        ])
    </x-admin.feature-card>
@endsection