@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Pemberian Pakan Koloni"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pemberian Pakan Koloni', 'route' => route('admin.care-livestock.feeding-colony.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.feeding-colony.edit', [$farm->id, $feedingColony->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.feeding-colony.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('admin.feeding-colony.show-component', ['farm' => $farm, 'feedingColony' => $feedingColony])
    </x-admin.feature-card>
@endsection
