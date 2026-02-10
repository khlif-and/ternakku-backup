@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Tambah Armada" :backUrl="route('shared.fleet.index', $farm->id)" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Armada', 'route' => route('shared.fleet.index', $farm->id)],
            ['label' => 'Tambah']
        ]">
        @livewire('shared.fleet.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection