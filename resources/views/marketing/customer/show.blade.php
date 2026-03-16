@extends('layouts.qurban.index')

@section('content')
    @livewire('marketing.customer.show-component', ['id' => $id])
@endsection