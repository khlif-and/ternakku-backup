@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Kandang" :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['route' => route('admin.care-livestock.pens.index', $farm->id), 'label' => 'Data Kandang'],
            ['label' => 'Detail']
        ]">
        @livewire('admin.pen.show-component', ['farm' => $farm, 'id' => $penId])
    </x-admin.feature-card>
@endsection