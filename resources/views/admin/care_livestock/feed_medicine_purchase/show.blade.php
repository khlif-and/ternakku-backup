@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Pembelian Pakan / Obat"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pembelian Pakan / Obat', 'route' => route('admin.care-livestock.feed-medicine-purchase.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.feed-medicine-purchase.edit', [$farm->id, $data->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.feed-medicine-purchase.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >
        @livewire('admin.feed-medicine-purchase.show-component', ['farm' => $farm, 'purchase' => $data])
    </x-admin.feature-card>
@endsection