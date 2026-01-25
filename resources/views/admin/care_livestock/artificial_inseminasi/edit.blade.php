@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Artificial Inseminasi" 
        :backUrl="route('admin.care-livestock.artificial-inseminasi.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Artificial Inseminasi', 'route' => route('admin.care-livestock.artificial-inseminasi.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.artificial-insemination.edit-component', [
            'farm' => $farm, 
            'item' => $item
        ])
    </x-admin.feature-card>
@endsection