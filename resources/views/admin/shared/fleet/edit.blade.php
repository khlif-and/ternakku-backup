@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Edit Armada" :backUrl="route('shared.fleet.index', $farm->id)" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Armada', 'route' => route('shared.fleet.index', $farm->id)],
            ['label' => 'Edit']
        ]">
        @livewire('shared.fleet.edit-component', ['farm' => $farm, 'id' => $fleet->id])
    </x-admin.feature-card>
@endsection