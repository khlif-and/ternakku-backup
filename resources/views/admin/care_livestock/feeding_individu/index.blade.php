@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Pemberian Pakan Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pemberian Pakan Individu']
        ]"
    >

        @livewire('admin.feeding-individu.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection
