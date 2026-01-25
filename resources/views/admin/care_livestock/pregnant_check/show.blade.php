@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Pregnant Check"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pregnant Check', 'route' => route('admin.care_livestock.pregnant_check.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care_livestock.pregnant_check.edit', [$farm->id, $item->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care_livestock.pregnant_check.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('admin.pregnant-check.show-component', ['farm' => $farm, 'item' => $item])
    </x-admin.feature-card>
@endsection