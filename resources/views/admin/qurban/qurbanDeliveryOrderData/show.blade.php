@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Data DO" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Data DO', 'route' => route('admin.qurban.delivery_order_qurban.index')],
            ['label' => 'Detail']
        ]" :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.qurban.delivery_order_qurban.edit', $delivery->id),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.qurban.delivery_order_qurban.index'),
                'type' => 'secondary'
            ]
        ]">

        @livewire('qurban.delivery-order-qurban.show-component', ['farm' => $farm, 'delivery' => $delivery])
    </x-admin.feature-card>
@endsection