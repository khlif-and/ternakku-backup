@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Artificial Inseminasi" 
        :backUrl="route('admin.care-livestock.artificial-inseminasi.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Artificial Inseminasi', 'route' => route('admin.care-livestock.artificial-inseminasi.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('admin.artificial-insemination.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection