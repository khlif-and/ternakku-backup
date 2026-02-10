@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Edit Pengemudi" :backUrl="route('shared.driver.index', $farm->id)" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Pengemudi', 'route' => route('shared.driver.index', $farm->id)],
            ['label' => 'Edit']
        ]">
        @livewire('shared.driver.edit-component', ['farm' => $farm, 'id' => $driver->id])
    </x-admin.feature-card>
@endsection