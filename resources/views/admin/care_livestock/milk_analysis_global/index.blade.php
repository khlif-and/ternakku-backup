@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Analisis Susu Global"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home', 'label' => 'Home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Analisis Susu Global']
        ]"
    >
        @livewire('admin.milk-analysis-global.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection