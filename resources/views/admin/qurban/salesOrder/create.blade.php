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
                <a href="{{ url('qurban/sales-order') }}">Data Sales Order</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Data Sales Order</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('qurban/sales-order') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="order_date">Tanggal Order</label>
                            <select name="customer_id" id="customer_id" required class="form-control">
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id}}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="order_date">Tanggal Order</label>
                            <input type="date" class="form-control" id="order_date" name="order_date" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Kuantitas</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="total_weight">Total Berat</label>
                            <input type="number" class="form-control" id="total_weight" name="total_weight" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" row="4"></textarea>
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
    $('#customer_id').select2();
});
</script>
@endsection
