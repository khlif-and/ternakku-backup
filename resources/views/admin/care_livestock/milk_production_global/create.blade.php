@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Produksi Susu Global" 
        :backUrl="route('admin.care-livestock.milk-production-global.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Produksi Susu Global', 'route' => route('admin.care-livestock.milk-production-global.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('admin.milk-production-global.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection