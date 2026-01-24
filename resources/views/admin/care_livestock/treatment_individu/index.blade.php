@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Treatment Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Treatment Individu']
        ]"
    >
        @livewire('admin.treatment-individu.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection