@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Surat Jalan" :backUrl="route('qurban.livestock-delivery-note.index')"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Surat Jalan Ternak', 'route' => route('qurban.livestock-delivery-note.index')],
            ['label' => 'Detail']
        ]">
        @livewire('qurban.livestock-delivery-note-qurban.show-component', ['farm' => $farm, 'deliveryNote' => $delivery])
    </x-admin.feature-card>
@endsection