@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Surat Jalan Qurban</h3>
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
                <a href="#">Aktivitas</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ url('qurban/qurban-delivery-order-data') }}">Surat Jalan Qurban</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Surat Jalan Qurban</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ url('qurban/qurban-delivery-order-data') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="recipient_name">Nama Penerima</label>
                            <input type="text" class="form-control" id="recipient_name" name="recipient_name" required placeholder="Masukkan nama penerima">
                        </div>

                        <div class="form-group mb-3">
                            <label for="livestock_type">Jenis Ternak</label>
                            <input type="text" class="form-control" id="livestock_type" name="livestock_type" required placeholder="Contoh: Sapi, Kambing">
                        </div>

                        <div class="form-group mb-3">
                            <label for="quantity">Jumlah Ternak</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required placeholder="Masukkan jumlah ekor">
                        </div>

                        <div class="form-group mb-3">
                            <label for="delivery_date">Tanggal Pengiriman</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes">Catatan Tambahan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Contoh: Pengiriman pagi sebelum jam 10..."></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan Surat Jalan</button>
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
