@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Pregnant Check"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pregnant Check']
        ]"
    >

        @livewire('admin.pregnant-check.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection