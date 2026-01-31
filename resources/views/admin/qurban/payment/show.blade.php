@extends('layouts.qurban.index')

@section('content')
    <x-admin.feature-card title="Detail Pembayaran"
        :breadcrumbs="[
            ['route' => '/', 'icon' => 'icon-home'],
            ['label' => 'Qurban'],
            ['label' => 'Payment', 'route' => route('admin.qurban.payment.index', $farm->id)],
            ['label' => 'Detail']
        ]"
        :actions="[
            [
                'label' => 'Edit',
                'route' => route('admin.qurban.payment.edit', [$farm->id, $payment->id]),
                'type' => 'primary'
            ],
            [
                'label' => 'Kembali',
                'route' => route('admin.qurban.payment.index', $farm->id),
                'type' => 'secondary'
            ]
        ]"
    >

        @livewire('qurban.payment.show-component', ['farm' => $farm, 'payment' => $payment])
    </x-admin.feature-card>
@endsection