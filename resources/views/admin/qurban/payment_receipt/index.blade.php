@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Payment Receipt</h3>
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
                <a href="#">Aktifitas</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Payment Receipt</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Payment Receipt</h4>
                        <a href="{{ route('payment_receipt.create') }}" class="btn btn-primary btn-round ms-auto">
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
                                    <th>Nama Pelanggan</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Jumlah Pembayaran</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($paymentReceipts as $paymentReceipt)
                                    <tr>
                                        <td>{{ $paymentReceipt->customer_name }}</td>
                                        <td>{{ $paymentReceipt->payment_method }}</td>
                                        <td>Rp {{ number_format($paymentReceipt->amount, 0, ',', '.') }}</td>
                                        <td>{{ $paymentReceipt->payment_date }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning">Edit</button>
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data bukti pembayaran.</td>
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
