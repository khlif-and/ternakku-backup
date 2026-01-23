@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Tambah Pemberian Pakan Koloni" :backUrl="route('admin.care-livestock.feeding-colony.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pemberian Pakan Koloni', 'route' => route('admin.care-livestock.feeding-colony.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >

        @livewire('admin.feeding-colony.create-component', ['farm' => $farm, 'fromPen' => $fromPen ?? null])
    </x-admin.feature-card>
@endsection
