@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card
        title="Edit Customer"
        :backUrl="route('admin.care-livestock.customer.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Customer', 'route' => route('admin.care-livestock.customer.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.customer.edit-component', [
            'farm' => $farm,
            'customer' => $customer
        ])
    </x-admin.feature-card>
@endsection