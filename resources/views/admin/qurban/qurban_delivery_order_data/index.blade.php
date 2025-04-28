@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Qurban Delivery Order Data</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ url('/') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Aktivitas</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ url('qurban/qurban-delivery-order-data') }}">Qurban Delivery Order Data</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Qurban Delivery Order</h4>
                        <a href="{{ route('qurban_delivery_order_data.create') }}" class="btn btn-primary btn-round ms-auto">
                            <i class="fa fa-plus"></i>
                            Tambah Data
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Delivery Order Number</th>
                                    <th>Date</th>
                                    <th>Farmer</th>
                                    <th>Destination</th>
                                    <th>Animal Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($qurbanDeliveryOrders as $qurbanDeliveryOrder)
                                    <tr>
                                        <td>{{ $qurbanDeliveryOrder->number }}</td>
                                        <td>{{ $qurbanDeliveryOrder->date }}</td>
                                        <td>{{ $qurbanDeliveryOrder->farmer_name }}</td>
                                        <td>{{ $qurbanDeliveryOrder->destination }}</td>
                                        <td>{{ $qurbanDeliveryOrder->animal_count }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Dummy Data sementara -->
                                    <tr>
                                        <td>DOQ-001</td>
                                        <td>2025-04-28</td>
                                        <td>Pak Budi</td>
                                        <td>Jakarta</td>
                                        <td>20</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#basic-datatables').DataTable();
    });
</script>
@endsection
