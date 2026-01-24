@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Analisis Susu Global" 
        :backUrl="route('admin.care-livestock.milk-analysis-global.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home', 'label' => 'Home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Analisis Susu Global', 'route' => route('admin.care-livestock.milk-analysis-global.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.milk-analysis-global.edit-component', [
            'farm' => $farm, 
            'milkAnalysisGlobal' => $milkAnalysisGlobal
        ])
    </x-admin.feature-card>
@endsection