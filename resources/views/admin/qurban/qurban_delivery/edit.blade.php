@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Proses Instruksi Pengiriman" :backUrl="route('admin.qurban.qurban_delivery.index')"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Instruksi Pengiriman', 'route' => route('admin.qurban.qurban_delivery.index')],
            ['label' => 'Proses']
        ]">
        @livewire('qurban.qurban-delivery.edit-component', ['farm' => $farm, 'delivery' => $delivery])
    </x-admin.feature-card>
@endsection