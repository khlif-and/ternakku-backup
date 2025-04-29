@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pengiriman Qurban</h3>
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
                <a href="{{ url('qurban-delivery') }}">Pengiriman Qurban</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Qurban Delivery</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('qurban-delivery') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="animal_number">Nomor Hewan</label>
                            <input type="text" class="form-control" id="animal_number" name="animal_number" required>
                        </div>

                        <div class="form-group">
                            <label for="driver_name">Nama Driver</label>
                            <input type="text" class="form-control" id="driver_name" name="driver_name" required>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="departure_time">Waktu Berangkat</label>
                                    <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="arrival_time">Waktu Sampai</label>
                                    <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Status Pengiriman</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="pending">Pending</option>
                                <option value="delivered">Delivered</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="photo">Unggah Bukti Foto (Opsional)</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
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

</script>
@endsection
