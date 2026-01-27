@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card
        title="Customer"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Customer']
        ]"
    >
        @livewire('admin.customer.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection