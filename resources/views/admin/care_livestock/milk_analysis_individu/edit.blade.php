@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Analisis Susu Individu" 
        :backUrl="route('admin.care-livestock.milk-analysis-individu.show', [$farm->id, $milkAnalysisIndividu->id])"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home', 'label' => 'Home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Analisis Susu Individu', 'route' => route('admin.care-livestock.milk-analysis-individu.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >
        @livewire('admin.milk-analysis-individu.edit-component', [
            'farm' => $farm, 
            'milkAnalysisIndividu' => $milkAnalysisIndividu
        ])
    </x-admin.feature-card>
@endsection