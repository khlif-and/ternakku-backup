@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Detail Treatment Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Treatment Individu', 'route' => route('admin.care-livestock.treatment-individu.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.treatment-individu.edit', [$farm->id, $treatmentIndividu->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.treatment-individu.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('admin.treatment-individu.show-component', ['farm' => $farm, 'treatmentIndividu' => $treatmentIndividu])
    </x-admin.feature-card>
@endsection