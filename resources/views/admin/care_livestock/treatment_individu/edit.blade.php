@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Treatment Individu" 
        :backUrl="route('admin.care-livestock.treatment-individu.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Treatment Individu', 'route' => route('admin.care-livestock.treatment-individu.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.treatment-individu.edit-component', [
            'farm' => $farm, 
            'treatmentIndividu' => $treatmentIndividu
        ])
    </x-admin.feature-card>
@endsection