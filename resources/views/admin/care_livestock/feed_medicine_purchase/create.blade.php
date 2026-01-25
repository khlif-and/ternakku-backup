@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Tambah Pembelian Pakan / Obat" :backUrl="route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pembelian Pakan / Obat', 'route' => route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >

        @livewire('admin.feed-medicine-purchase.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection