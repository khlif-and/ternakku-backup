@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Sales Order Details"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Sales Order', 'route' => route('admin.care-livestock.sales-order.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.sales-order.edit', [$farm->id, $id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Back',
                'route' => route('admin.care-livestock.sales-order.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >
        @livewire('qurban.sales-order.show-component', ['farm' => $farm, 'salesOrder' => $id])
    </x-admin.feature-card>
@endsection