@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Analisis Susu Global" 
        :backUrl="route('admin.care-livestock.milk-analysis-global.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home', 'label' => 'Home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Analisis Susu Global', 'route' => route('admin.care-livestock.milk-analysis-global.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('admin.milk-analysis-global.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection