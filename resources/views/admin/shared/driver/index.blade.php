@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Data Pengemudi" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Pengemudi']
        ]">
        @livewire('shared.driver.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection