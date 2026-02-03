@extends('layouts.driver.index')

@section('page-title', 'Detail Pengiriman')

@section('content')
    @livewire('driver.delivery.show-component', ['id' => $id])
@endsection