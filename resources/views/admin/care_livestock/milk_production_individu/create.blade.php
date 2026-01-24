@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Produksi Susu Individu" 
        :backUrl="route('admin.care-livestock.milk-production-individu.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Produksi Susu Individu', 'route' => route('admin.care-livestock.milk-production-individu.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('admin.milk-production-individu.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection