@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Penerimaan Ternak Qurban" icon="icon-box" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Penerimaan Ternak']
        ]">
        @livewire('qurban.livestock-reception-qurban.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection