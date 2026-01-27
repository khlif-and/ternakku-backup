@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card
        title="Customer Details"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Customer', 'route' => route('admin.care-livestock.customer.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.customer.edit', [$farm->id, $customer->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Back',
                'route' => route('admin.care-livestock.customer.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >
        @livewire('admin.customer.show-component', ['farm' => $farm, 'customer' => $customer])
    </x-admin.feature-card>
@endsection