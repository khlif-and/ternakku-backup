@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Livestock Birth" 
        :backUrl="route('admin.care_livestock.livestock_birth.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Livestock Birth', 'route' => route('admin.care_livestock.livestock_birth.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.livestock-birth.edit-component', [
            'farm' => $farm, 
            'birth' => $birth
        ])
    </x-admin.feature-card>
@endsection