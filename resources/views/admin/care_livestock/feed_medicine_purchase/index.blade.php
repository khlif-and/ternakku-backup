@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Pembelian Pakan / Obat"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pembelian Pakan / Obat']
        ]"
    >

        @livewire('admin.feed-medicine-purchase.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection