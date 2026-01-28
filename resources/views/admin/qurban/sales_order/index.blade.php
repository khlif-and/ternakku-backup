@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Sales Order" 
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Sales Order']
        ]"
    >
        @livewire('qurban.sales-order.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection