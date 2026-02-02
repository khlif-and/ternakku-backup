@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Edit Data DO" :backUrl="route('admin.qurban.delivery_order_qurban.index', ['farm_id' => $farm->id ?? null])"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Data DO', 'route' => route('admin.qurban.delivery_order_qurban.index')],
            ['label' => 'Edit']
        ]">
        @livewire('qurban.delivery-order-qurban.edit-component', ['farm' => $farm, 'delivery' => $delivery])
    </x-admin.feature-card>
@endsection