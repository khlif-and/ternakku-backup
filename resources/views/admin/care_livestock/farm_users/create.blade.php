@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Tambah Pengguna" :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['route' => route('admin.care-livestock.farm-users.index', $farm->id), 'label' => 'Data Pengguna'],
            ['label' => 'Tambah']
        ]">
        @livewire('admin.farm-user.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection