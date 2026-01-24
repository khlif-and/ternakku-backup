@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Detail Analisis Susu Global"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home', 'label' => 'Home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Analisis Susu Global', 'route' => route('admin.care-livestock.milk-analysis-global.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.care-livestock.milk-analysis-global.edit', [$farm->id, $milkAnalysisGlobal->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.care-livestock.milk-analysis-global.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >
        @livewire('admin.milk-analysis-global.show-component', [
            'farm' => $farm, 
            'milkAnalysisGlobal' => $milkAnalysisGlobal
        ])
    </x-admin.feature-card>
@endsection