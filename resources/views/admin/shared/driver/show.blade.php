@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Pengemudi" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Pengemudi', 'route' => route('shared.driver.index', $farm->id)],
            ['label' => 'Detail']
        ]">
        @livewire('shared.driver.show-component', ['farm' => $farm, 'id' => $driver->id])
    </x-admin.feature-card>
@endsection