@extends('layouts.qurban.index')

@section('content')
    @livewire('marketing.sales-order.show-component', ['id' => $id])
@endsection