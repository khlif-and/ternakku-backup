@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Analisis Susu Individu" 
        :backUrl="route('admin.care-livestock.milk-analysis-individu.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home', 'label' => 'Home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Analisis Susu Individu', 'route' => route('admin.care-livestock.milk-analysis-individu.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('admin.milk-analysis-individu.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection