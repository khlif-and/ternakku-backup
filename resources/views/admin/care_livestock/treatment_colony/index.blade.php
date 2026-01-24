@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Treatment Koloni"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Treatment Koloni']
        ]"
    >

        @livewire('admin.treatment-colony.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection