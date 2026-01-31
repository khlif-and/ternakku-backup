@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card 
        title="Tambah Pembayaran" 
        :backUrl="route('admin.qurban.payment.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Payment', 'route' => route('admin.qurban.payment.index', $farm->id)],
            ['label' => 'Tambah']
        ]"
    >
        @livewire('qurban.payment.create-component', ['farm' => $farm])
    </x-admin.feature-card>
@endsection