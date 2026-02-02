@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Data Armada" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Armada']
        ]">
        @livewire('shared.fleet.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection