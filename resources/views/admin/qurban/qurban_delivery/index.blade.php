@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card 
            title="Data Pengiriman Qurban" 
            :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Pengiriman']
        ]"
        >
            @livewire('qurban.qurban-delivery.index-component', ['farm' => $farm])
        </x-admin.feature-card>
@endsection