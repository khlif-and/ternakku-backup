@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Livestock Birth"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Livestock Birth']
        ]"
    >
        @livewire('admin.livestock-birth.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection