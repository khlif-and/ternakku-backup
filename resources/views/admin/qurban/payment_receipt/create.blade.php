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
                <a href="{{ url('qurban/payment-receipt') }}">Payment Receipt</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Payment Receipt</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ url('qurban/payment-receipt') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="customer">Nama Pelanggan</label>
                            <select class="form-control" id="customer" name="customer" required>
                                <option value="">Pilih Pelanggan</option>
                                <option value="1">Pelanggan 1</option>
                                <option value="2">Pelanggan 2</option>
                                <option value="3">Pelanggan 3</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="payment_method">Metode Pembayaran</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer_bank">Transfer Bank</option>
                                <option value="cash">Cash</option>
                                <option value="e_wallet">E-Wallet</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Jumlah Pembayaran (Rp)</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="payment_date">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Tambahkan jika ada script khusus nanti
    });
</script>
@endsection
