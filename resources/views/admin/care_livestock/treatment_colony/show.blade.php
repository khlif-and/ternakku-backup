@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Treatment Koloni"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Treatment Koloni', 'route' => route('admin.care-livestock.treatment-colony.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.treatment-colony.edit', [$farm->id, $treatmentColony->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.treatment-colony.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('admin.treatment-colony.show-component', ['farm' => $farm, 'treatmentColony' => $treatmentColony])
    </x-admin.feature-card>
@endsection