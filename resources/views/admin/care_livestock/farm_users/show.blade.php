@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Pengguna" :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['route' => route('admin.care-livestock.farm-users.index', $farm->id), 'label' => 'Data Pengguna'],
            ['label' => 'Detail']
        ]">
        @livewire('admin.farm-user.show-component', ['farm' => $farm, 'id' => $id])
    </x-admin.feature-card>
@endsection