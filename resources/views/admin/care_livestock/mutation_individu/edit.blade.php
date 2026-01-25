@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Mutasi Individu" 
        :backUrl="route('admin.care-livestock.mutation-individu.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Mutasi Individu', 'route' => route('admin.care-livestock.mutation-individu.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.mutation-individu.edit-component', [
            'farm' => $farm, 
            'mutationIndividu' => $mutationIndividu
        ])
    </x-admin.feature-card>
@endsection