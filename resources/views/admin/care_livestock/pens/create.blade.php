@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Tambah Kandang" :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['route' => route('admin.care-livestock.pens.index', $farm->id), 'label' => 'Data Kandang'],
            ['label' => 'Tambah']
        ]">
        @livewire('admin.pen.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection