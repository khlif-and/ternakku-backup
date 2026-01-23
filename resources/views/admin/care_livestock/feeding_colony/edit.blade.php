@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Edit Pemberian Pakan Koloni" :backUrl="route('admin.care-livestock.feeding-colony.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pemberian Pakan Koloni', 'route' => route('admin.care-livestock.feeding-colony.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >

        @livewire('admin.feeding-colony.edit-component', ['farm' => $farm, 'feedingColony' => $feedingColony])
    </x-admin.feature-card>
@endsection
