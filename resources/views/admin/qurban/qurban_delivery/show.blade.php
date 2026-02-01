@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Pengiriman" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Pengiriman', 'route' => route('admin.qurban.qurban_delivery.index', $farm->id)],
            ['label' => 'Detail']
        ]" :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.qurban.qurban_delivery.edit', [$farm->id, $delivery->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.qurban.qurban_delivery.index', $farm->id),
                'type' => 'secondary'
            ]
        ]">

        @livewire('qurban.qurban-delivery.show-component', ['farm' => $farm, 'delivery' => $delivery])
    </x-admin.feature-card>
@endsection