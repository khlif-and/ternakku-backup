@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Tambah Penerimaan Ternak Qurban" :backUrl="route('qurban.livestock-reception.index')"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Penerimaan Ternak', 'route' => route('qurban.livestock-reception.index')],
            ['label' => 'Tambah']
        ]">
        @livewire('qurban.livestock-reception-qurban.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection