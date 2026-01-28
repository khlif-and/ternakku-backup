@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Detail Penjualan Ternak" 
        :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['route' => route('admin.care-livestock.sales-livestock.index', $farm->id), 'label' => 'Penjualan Ternak'],
            ['label' => 'Detail']
        ]"
        backUrl="{{ route('admin.care-livestock.sales-livestock.index', $farm->id) }}"
    >
        @livewire('qurban.sales-livestock.show-component', ['farm' => $farm, 'id' => $id])
    </x-admin.feature-card>
@endsection
