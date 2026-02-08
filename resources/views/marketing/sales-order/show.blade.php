@extends('layouts.marketing.index')

@section('page-title', 'Detail Sales Order')

@section('content')
    @livewire('marketing.sales-order.show-component', ['id' => $id])
@endsection