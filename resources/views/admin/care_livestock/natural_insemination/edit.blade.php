@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Natural Insemination" 
        :backUrl="route('admin.care-livestock.natural-insemination.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Natural Insemination', 'route' => route('admin.care-livestock.natural-insemination.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.natural-insemination.edit-component', [
            'farm' => $farm, 
            'item' => $item
        ])
    </x-admin.feature-card>
@endsection