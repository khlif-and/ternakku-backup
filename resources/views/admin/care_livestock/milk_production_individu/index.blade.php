@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Produksi Susu Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Produksi Susu Individu']
        ]"
    >
        @livewire('admin.milk-production-individu.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection