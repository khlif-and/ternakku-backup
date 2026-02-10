@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Armada" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Armada', 'route' => route('shared.fleet.index', $farm->id)],
            ['label' => 'Detail']
        ]">
        @livewire('shared.fleet.show-component', ['farm' => $farm, 'id' => $fleet->id])
    </x-admin.feature-card>
@endsection