@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card title="Artificial Inseminasi"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Care Livestock'],
            ['label' => 'Artificial Inseminasi']
        ]"
    >
        @livewire('admin.artificial-insemination.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection