@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Mutasi Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Mutasi Individu']
        ]"
    >
        @livewire('admin.mutation-individu.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection