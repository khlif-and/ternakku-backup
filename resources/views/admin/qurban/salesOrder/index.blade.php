@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Data Sales Order</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Data Awal</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Data Sales Order</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Sales Order</h4>
                        <a class="btn btn-primary btn-round ms-auto" href="{{ url('qurban/sales-order/create') }}">
                            <i class="fa fa-plus"></i>
                            Tambah Data
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover" >
                            <thead>
                                <tr>
                                    <th>Tanggal Order</th>
                                    <th>Customer</th>
                                    <th>Kuantitas</th>
                                    <th>Total Berat</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($salesOrders as $salesOrder)
                                <tr>
                                    <td>{{ $salesOrder->order_date }}</td>
                                    <td>{{ $salesOrder->qurbanCustomer->name }}</td>
                                    <td>{{ $salesOrder->quantity }}</td>
                                    <td>{{ $salesOrder->total_weight }}</td>
                                    <td>{{ $salesOrder->description }}</td>
                                    <td>
                                        <a href="{{ url('qurban/sales-order/' . $salesOrder->id . '/edit') }}" class="btn btn-warning btn-sm me-2">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ url('qurban/sales-order/' . $salesOrder->id) }}" method="post" class="d-inline me-2" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
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
<script >
    $(document).ready(function() {
        $('#basic-datatables').DataTable({
        });
    });
</script>
@endsection
