@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Tambah Pemberian Pakan Individu" :backUrl="route('admin.care-livestock.feeding-individu.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pemberian Pakan Individu', 'route' => route('admin.care-livestock.feeding-individu.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >

        @livewire('admin.feeding-individu.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection


