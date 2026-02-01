@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Edit Pengiriman" :backUrl="route('admin.qurban.qurban_delivery.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Pengiriman', 'route' => route('admin.qurban.qurban_delivery.index', $farm->id)],
            ['label' => 'Edit']
        ]">
        @livewire('qurban.qurban-delivery.edit-component', ['farm' => $farm, 'delivery' => $delivery])
    </x-admin.feature-card>
@endsection