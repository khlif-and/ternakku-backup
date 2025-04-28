@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Data Timbang Ulang</h3>
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
                <a href="{{ url('qurban/weight') }}">Data Timbang Ulang</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Data Timbang Ulang</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('qurban/weight') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="weight">Berat (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="weight" name="weight" required>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="pbb">PBB (kg)</label>
                                    <input type="number" step="0.01" class="form-control" id="pbb" name="pbb" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="dof">DOF (Hari)</label>
                                    <input type="number" class="form-control" id="dof" name="dof" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="adg">ADG (kg/hari)</label>
                                    <input type="number" step="0.01" class="form-control" id="adg" name="adg" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="date">Tanggal</label>
                            <input type="date" class="form-control" id="date" name="date" required>
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
