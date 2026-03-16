@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Penerimaan Ternak Qurban" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Penerimaan Ternak', 'route' => route('qurban.livestock-reception.index')],
            ['label' => 'Detail']
        ]" :actions="[
            [
                'label' => 'Edit',
                'route' => route('qurban.livestock-reception.edit', $reception->id),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('qurban.livestock-reception.index'),
                'type' => 'secondary'
            ]
        ]">

        @livewire('qurban.livestock-reception-qurban.show-component', ['farm' => $farm, 'reception' => $reception])
    </x-admin.feature-card>
@endsection