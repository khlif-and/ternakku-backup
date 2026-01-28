@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Penjualan Ternak" 
        :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['label' => 'Penjualan Ternak']
        ]"
    >
        @livewire('qurban.sales-livestock.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection
