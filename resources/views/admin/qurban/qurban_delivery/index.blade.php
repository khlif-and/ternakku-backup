@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Instruksi Pengiriman Qurban" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Instruksi Pengiriman']
        ]">
        @livewire('qurban.qurban-delivery.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection