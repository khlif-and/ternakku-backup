@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Surat Jalan" icon="icon-truck" :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Surat Jalan']
        ]">
        @livewire('qurban.livestock-delivery-note-qurban.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection