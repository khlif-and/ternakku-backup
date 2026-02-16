@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Edit Penerimaan Ternak Qurban" :backUrl="route('qurban.livestock-reception.index')"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Penerimaan Ternak', 'route' => route('qurban.livestock-reception.index')],
            ['label' => 'Edit']
        ]">
        @livewire('qurban.livestock-reception-qurban.edit-component', ['farm' => $farm, 'reception' => $reception])
    </x-admin.feature-card>
@endsection