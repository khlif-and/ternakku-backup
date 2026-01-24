@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Edit Treatment Koloni" :backUrl="route('admin.care-livestock.treatment-colony.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Treatment Koloni', 'route' => route('admin.care-livestock.treatment-colony.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >

        @livewire('admin.treatment-colony.edit-component', ['farm' => $farm, 'treatmentColony' => $treatmentColony])
    </x-admin.feature-card>
@endsection