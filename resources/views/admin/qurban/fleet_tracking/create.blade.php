@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pelacakan Armada</h3>
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
                <a href="{{ url('fleet-tracking') }}">Pelacakan Armada</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Data Pelacakan Armada</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('fleet-tracking') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="driver_name">Nama Pengemudi</label>
                            <input type="text" class="form-control" id="driver_name" name="driver_name" required>
                        </div>

                        <div class="form-group">
                            <label for="vehicle">Nama Kendaraan</label>
                            <input type="text" class="form-control" id="vehicle" name="vehicle" required>
                        </div>

                        <div class="form-group">
                            <label for="departure_time">Waktu Keberangkatan</label>
                            <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" required>
                        </div>

                        <div class="form-group">
                            <label for="last_location">Lokasi Terakhir Diketahui</label>
                            <textarea class="form-control" id="last_location" name="last_location" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="tracking_date">Tanggal Pelacakan</label>
                            <input type="date" class="form-control" id="tracking_date" name="tracking_date" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status Armada</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="on_the_way">Dalam Perjalanan</option>
                                <option value="delivered">Terkirim</option>
                                <option value="delayed">Tertunda</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="photo">Unggah Foto</label>
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
