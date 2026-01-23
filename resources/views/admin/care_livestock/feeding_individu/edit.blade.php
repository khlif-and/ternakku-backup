@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Edit Pemberian Pakan Individu" :backUrl="route('admin.care-livestock.feeding-individu.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pemberian Pakan Individu', 'route' => route('admin.care-livestock.feeding-individu.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >

        @livewire('admin.feeding-individu.edit-component', ['farm' => $farm, 'feedingIndividu' => $feedingIndividu])
    </x-admin.feature-card>
@endsection
