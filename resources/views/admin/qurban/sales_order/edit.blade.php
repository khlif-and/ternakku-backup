@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Sales Order" 
        :backUrl="route('admin.care-livestock.sales-order.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Sales Order', 'route' => route('admin.care-livestock.sales-order.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('qurban.sales-order.edit-component', [
            'farm' => $farm, 
            'salesOrder' => $salesOrder
        ])
    </x-admin.feature-card>
@endsection