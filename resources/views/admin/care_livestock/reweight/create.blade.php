@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Penimbangan" 
        :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['route' => route('admin.care-livestock.reweight.index', $farm->id), 'label' => 'Penimbangan Ulang'],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('farming.reweight.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection
