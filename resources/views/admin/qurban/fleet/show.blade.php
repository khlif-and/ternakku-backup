@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Armada" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Armada', 'route' => route('shared.fleet.index', $farm->id)],
            ['label' => 'Detail']
        ]" :actions="[
            [
                'label' => 'Edit',
                'route' => route('shared.fleet.edit', [$farm->id, $fleet->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('shared.fleet.index', $farm->id),
                'type' => 'secondary'
            ]
        ]">
        @livewire('shared.fleet.show-component', ['farm' => $farm, 'id' => $fleet->id])
    </x-admin.feature-card>
@endsection