@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Edit Pengguna" :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['route' => route('admin.care-livestock.farm-users.index', $farm->id), 'label' => 'Data Pengguna'],
            ['label' => 'Edit']
        ]">
        @livewire('admin.farm-user.edit-component', ['farm' => $farm, 'id' => $id])
    </x-admin.feature-card>
@endsection