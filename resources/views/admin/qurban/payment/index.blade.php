@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Data Pembayaran"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Payment']
        ]"
    >

        @livewire('qurban.payment.index-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection