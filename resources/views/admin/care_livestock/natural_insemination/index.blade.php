@extends('layouts.care_livestock.index')

@section('content') <x-admin.feature-card title="Natural Insemination" :breadcrumbs="[ ['route' => '/', 'icon' => 'icon-home'], ['label' => 'Care Livestock'], ['label' => 'Natural Insemination'] ]" > @livewire('admin.natural-insemination.index-component', ['farm' => $farm]) </x-admin.feature-card> @endsection