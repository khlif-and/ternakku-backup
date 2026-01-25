@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Edit Pregnant Check" :backUrl="route('admin.care_livestock.pregnant_check.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Pregnant Check', 'route' => route('admin.care_livestock.pregnant_check.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >

        @livewire('admin.pregnant-check.edit-component', ['farm' => $farm, 'item' => $item])
    </x-admin.feature-card>
@endsection