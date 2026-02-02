@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Data Delivery Order Qurban" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Data DO']
        ]">
        @livewire('qurban.delivery-order-qurban.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection