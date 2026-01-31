@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Tambah Surat Jalan" :backUrl="route('qurban.livestock-delivery-note.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Surat Jalan', 'route' => route('qurban.livestock-delivery-note.index', $farm->id)],
            ['label' => 'Tambah']
        ]">
        @livewire('qurban.livestock-delivery-note-qurban.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection