@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Produksi Susu Global"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Produksi Susu Global', 'route' => route('admin.care-livestock.milk-production-global.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.milk-production-global.edit', [$farm->id, $milkProductionGlobal->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.milk-production-global.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >
        @livewire('admin.milk-production-global.show-component', ['farm' => $farm, 'milkProductionGlobal' => $milkProductionGlobal])
    </x-admin.feature-card>
@endsection