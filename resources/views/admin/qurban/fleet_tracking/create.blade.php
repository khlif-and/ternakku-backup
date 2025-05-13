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
                <a href="{{ url('qurban/fleet-tracking') }}">Fleet Tracking</a>
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
                    <form action="{{ url('qurban/fleet-tracking') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="pelanggan">Pelanggan</label>
                            <select class="form-control" id="pelanggan" name="pelanggan" required>
                                <option value="">Pilih Pelanggan</option>
                                <option value="1">Pelanggan 1</option>
                                <option value="2">Pelanggan 2</option>
                                <option value="3">Pelanggan 3</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="ternak">Jenis Ternak</label>
                            <select class="form-control" id="ternak" name="ternak" required>
                                <option value="">Pilih Ternak</option>
                                <option value="1">Sapi 1</option>
                                <option value="2">Sapi 2</option>
                                <option value="3">Kambing 1</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jumlah">Jumlah Ternak</label>
                            <select class="form-control" id="jumlah" name="jumlah" required>
                                <option value="">Pilih Jumlah</option>
                                <option value="1">1 Ekor</option>
                                <option value="2">2 Ekor</option>
                                <option value="3">3 Ekor</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal Pengiriman</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
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
