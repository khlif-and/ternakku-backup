@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Livestock Birth" 
        :backUrl="route('admin.care_livestock.livestock_birth.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Livestock Birth', 'route' => route('admin.care_livestock.livestock_birth.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('admin.livestock-birth.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection