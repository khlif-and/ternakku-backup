@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Edit Pembayaran" :backUrl="route('admin.qurban.payment.index', $farm->id)"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Payment', 'route' => route('admin.qurban.payment.index', $farm->id)],
            ['label' => 'Edit']
        ]"
    >

        @livewire('qurban.payment.edit-component', ['farm' => $farm, 'payment' => $payment])
    </x-admin.feature-card>
@endsection