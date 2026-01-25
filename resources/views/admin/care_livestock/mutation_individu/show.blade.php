@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Mutasi Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Mutasi Individu', 'route' => route('admin.care-livestock.mutation-individu.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.mutation-individu.edit', [$farm->id, $mutationIndividu->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.mutation-individu.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('admin.mutation-individu.show-component', [
            'farm' => $farm, 
            'mutationIndividu' => $mutationIndividu
        ])
    </x-admin.feature-card>
@endsection