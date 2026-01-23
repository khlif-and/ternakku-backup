@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Pemberian Pakan Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pemberian Pakan Individu', 'route' => route('admin.care-livestock.feeding-individu.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.feeding-individu.edit', ['farm_id' => $farm->id, 'id' => $feedingIndividu->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.feeding-individu.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('admin.feeding-individu.show-component', ['farm' => $farm, 'feedingIndividu' => $feedingIndividu])
    </x-admin.feature-card>
@endsection
