@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Data Pengguna" :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['label' => 'Data Pengguna']
        ]">
        @livewire('admin.farm-user.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection