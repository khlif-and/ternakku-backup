@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Edit Surat Jalan" :backUrl="route('qurban.livestock-delivery-note.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Surat Jalan', 'route' => route('qurban.livestock-delivery-note.index', $farm->id)],
            ['label' => 'Edit']
        ]">
        @livewire('qurban.livestock-delivery-note-qurban.edit-component', ['farm' => $farm, 'deliveryNote' => $deliveryNote])
    </x-admin.feature-card>
@endsection