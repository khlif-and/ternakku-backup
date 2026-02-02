@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Tambah Data DO" :backUrl="route('admin.qurban.delivery_order_qurban.index', ['farm_id' => $farm->id ?? null])" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Data DO', 'route' => route('admin.qurban.delivery_order_qurban.index')],
            ['label' => 'Tambah']
        ]">
        @livewire('qurban.delivery-order-qurban.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection