@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Produksi Susu Global"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Produksi Susu Global']
        ]"
    >
        @livewire('admin.milk-production-global.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection