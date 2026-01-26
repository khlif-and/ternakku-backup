@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Livestock Birth"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Livestock Birth', 'route' => route('admin.care_livestock.livestock_birth.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care_livestock.livestock_birth.edit', [$farm->id, $birth->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care_livestock.livestock_birth.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('admin.livestock-birth.show-component', ['farm' => $farm, 'birth' => $birth])
    </x-admin.feature-card>
@endsection