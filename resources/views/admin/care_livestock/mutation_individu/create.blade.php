@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Mutasi Individu" 
        :backUrl="route('admin.care-livestock.mutation-individu.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Mutasi Individu', 'route' => route('admin.care-livestock.mutation-individu.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('admin.mutation-individu.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection