@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Produksi Susu Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Produksi Susu Individu', 'route' => route('admin.care-livestock.milk-production-individu.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.milk-production-individu.edit', [$farm->id, $milkProductionIndividu->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.milk-production-individu.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >
        @livewire('admin.milk-production-individu.show-component', [
            'farm' => $farm, 
            'milkProductionIndividu' => $milkProductionIndividu
        ])
    </x-admin.feature-card>
@endsection