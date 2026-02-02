@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Instruksi Pengiriman" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Instruksi Pengiriman', 'route' => route('admin.qurban.qurban_delivery.index')],
            ['label' => 'Detail']
        ]">
        @livewire('qurban.qurban-delivery.show-component', ['farm' => $farm, 'delivery' => $delivery])
    </x-admin.feature-card>
@endsection