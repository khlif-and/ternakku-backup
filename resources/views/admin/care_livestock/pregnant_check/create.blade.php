@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Pregnant Check" 
        :backUrl="route('admin.care_livestock.pregnant_check.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pregnant Check', 'route' => route('admin.care_livestock.pregnant_check.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('admin.pregnant-check.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection