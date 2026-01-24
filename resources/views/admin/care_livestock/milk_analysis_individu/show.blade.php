@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Analisis Susu Individu"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home', 'label' => 'Home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Analisis Susu Individu', 'route' => route('admin.care-livestock.milk-analysis-individu.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.milk-analysis-individu.edit', [$farm->id, $milkAnalysisIndividu->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.milk-analysis-individu.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >
        @livewire('admin.milk-analysis-individu.show-component', [
            'farm' => $farm, 
            'milkAnalysisIndividu' => $milkAnalysisIndividu
        ])
    </x-admin.feature-card>
@endsection