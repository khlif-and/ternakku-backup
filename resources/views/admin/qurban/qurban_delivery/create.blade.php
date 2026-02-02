@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Tambah Instruksi Pengiriman" :backUrl="route('admin.qurban.qurban_delivery.index')"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Instruksi Pengiriman', 'route' => route('admin.qurban.qurban_delivery.index')],
            ['label' => 'Tambah']
        ]">
        @livewire('qurban.qurban-delivery.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection