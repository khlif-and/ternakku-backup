@extends('layouts.marketing.index')

@section('page-title', 'Detail Pelanggan')

@section('content')
    @livewire('marketing.customer.show-component', ['id' => $id])
@endsection