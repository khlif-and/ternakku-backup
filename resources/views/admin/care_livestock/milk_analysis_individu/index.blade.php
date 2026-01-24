@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Analisis Susu Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home', 'label' => 'Home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Analisis Susu Individu']
        ]"
    >
        @livewire('admin.milk-analysis-individu.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection