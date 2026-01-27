@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card
        title="Add Customer"
        :backUrl="route('admin.care-livestock.customer.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Customer', 'route' => route('admin.care-livestock.customer.index', $farm->id)],
            ['label' => 'Add']
        ]"
    >
        @livewire('admin.customer.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection