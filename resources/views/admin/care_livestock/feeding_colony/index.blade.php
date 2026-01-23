@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Pemberian Pakan Koloni"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pemberian Pakan Koloni']
        ]"
    >

        @livewire('admin.feeding-colony.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection
