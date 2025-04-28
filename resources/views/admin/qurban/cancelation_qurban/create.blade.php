@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Cancelation Qurban</h3>
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
                <a href="#">Aktifitas</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ url('qurban/cancelation-qurban') }}">Cancelation Qurban</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Cancelation Qurban</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ url('qurban/cancelation-qurban') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="animal_number">Nomor Hewan</label>
                            <input type="text" class="form-control" id="animal_number" name="animal_number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="cancel_reason">Alasan Pembatalan</label>
                            <input type="text" class="form-control" id="cancel_reason" name="cancel_reason" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="cancel_date">Tanggal Pembatalan</label>
                            <input type="date" class="form-control" id="cancel_date" name="cancel_date" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="note">Catatan</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
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
        // Tambahkan script tambahan di sini jika perlu
    });
</script>
@endsection
