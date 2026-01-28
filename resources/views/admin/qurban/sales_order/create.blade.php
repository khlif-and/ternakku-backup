@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card
        title="Add Sales Order"
        :backUrl="route('admin.care-livestock.sales-order.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Sales Order', 'route' => route('admin.care-livestock.sales-order.index', $farm->id)],
            ['label' => 'Add']
        ]"
    >
        @livewire('qurban.sales-order.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection