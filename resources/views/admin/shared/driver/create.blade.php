@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Tambah Pengemudi" :backUrl="route('shared.driver.index', $farm->id)" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Pengemudi', 'route' => route('shared.driver.index', $farm->id)],
            ['label' => 'Tambah']
        ]">
        @livewire('shared.driver.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection