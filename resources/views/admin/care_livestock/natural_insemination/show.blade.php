@extends('layouts.care_livestock.index')

@section('content') <x-admin.feature-card title="Natural Insemination Details" :breadcrumbs="[ ['route' => '/', 'icon' => 'icon-home'], ['label' => 'Care Livestock'], ['label' => 'Natural Insemination', 'route' => route('admin.care-livestock.natural-insemination.index', $farm->id)], ['label' => 'Detail'] ]" :actions="[ [ 'label' => 'Edit', 'route' => route('admin.care-livestock.natural-insemination.edit', [$farm->id, $item->id]), 'type' => 'primary' ], [ 'label' => 'Back', 'route' => route('admin.care-livestock.natural-insemination.index', $farm->id), 'type' => 'secondary' ] ]" >

    @livewire('admin.natural-insemination.show-component', ['farm' => $farm, 'item' => $item])
</x-admin.feature-card>
@endsection