@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Artificial Inseminasi"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Artificial Inseminasi', 'route' => route('admin.care-livestock.artificial-inseminasi.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.artificial-inseminasi.edit', [$farm->id, $item->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.artificial-inseminasi.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('admin.artificial-insemination.show-component', ['farm' => $farm, 'item' => $item])
    </x-admin.feature-card>
@endsection