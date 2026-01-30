@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Penimbangan Ulang" 
        :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['label' => 'Penimbangan Ulang']
        ]"
    >
        @livewire('farming.reweight.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection
