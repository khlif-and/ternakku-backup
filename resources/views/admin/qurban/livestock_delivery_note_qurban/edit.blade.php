@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Edit Jadwal Pengiriman" :backUrl="route('qurban.livestock-delivery-note.index')"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Surat Jalan Ternak', 'route' => route('qurban.livestock-delivery-note.index')],
            ['label' => 'Edit Jadwal']
        ]">
        @livewire('qurban.livestock-delivery-note-qurban.edit-component', ['farm' => $farm, 'deliveryNote' => $delivery])
    </x-admin.feature-card>
@endsection