@extends('layouts.care_livestock.index')

@section('content')
    <x-admin.feature-card 
        title="Edit Penimbangan" 
        :breadcrumbs="[
            ['route' => route('care_livestock'), 'icon' => 'icon-home', 'label' => 'Care Livestock'],
            ['route' => route('admin.care-livestock.reweight.index', $farm->id), 'label' => 'Penimbangan Ulang'],
            ['label' => 'Edit']
        ]"
    >
        @livewire('farming.reweight.edit-component', ['farm' => $farm, 'id' => $id ?? request()->route('reweight')])
    </x-admin.feature-card>
@endsection
