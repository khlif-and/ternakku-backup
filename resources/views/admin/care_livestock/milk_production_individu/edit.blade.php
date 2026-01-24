@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Produksi Susu Individu" 
        :backUrl="route('admin.care-livestock.milk-production-individu.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Produksi Susu Individu', 'route' => route('admin.care-livestock.milk-production-individu.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.milk-production-individu.edit-component', [
            'farm' => $farm, 
            'milkProductionIndividu' => $milkProductionIndividu
        ])
    </x-admin.feature-card>
@endsection