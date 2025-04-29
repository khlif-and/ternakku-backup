@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Pendaftaran Tabungan</h3>
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
                <a href="{{ url('saving-registration') }}">Pendaftaran Tabungan</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Data Pendaftaran Tabungan</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('saving-registration') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="livestock_type_name">Jenis Ternak</label>
                            <input type="text" class="form-control" id="livestock_type_name" name="livestock_type_name" required>
                        </div>

                        <div class="form-group">
                            <label for="livestock_breed_name">Nama Ternak</label>
                            <input type="text" class="form-control" id="livestock_breed_name" name="livestock_breed_name" required>
                        </div>

                        <div class="form-group">
                            <label for="farm_name">Nama Peternakan</label>
                            <input type="text" class="form-control" id="farm_name" name="farm_name" required>
                        </div>

                        <div class="form-group">
                            <label for="weight">Berat (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="weight" name="weight" required>
                        </div>

                        <div class="form-group">
                            <label for="price_per_kg">Harga per Kg (Rp)</label>
                            <input type="number" class="form-control" id="price_per_kg" name="price_per_kg" required>
                        </div>

                        <div class="form-group">
                            <label for="price_total">Harga Total (Rp)</label>
                            <input type="number" class="form-control" id="price_total" name="price_total" required>
                        </div>

                        <div class="form-group">
                            <label for="province_name">Provinsi</label>
                            <input type="text" class="form-control" id="province_name" name="province_name" required>
                        </div>

                        <div class="form-group">
                            <label for="regency_name">Kabupaten/Kota</label>
                            <input type="text" class="form-control" id="regency_name" name="regency_name" required>
                        </div>

                        <div class="form-group">
                            <label for="district_name">Kecamatan</label>
                            <input type="text" class="form-control" id="district_name" name="district_name" required>
                        </div>

                        <div class="form-group">
                            <label for="village_name">Kelurahan/Desa</label>
                            <input type="text" class="form-control" id="village_name" name="village_name" required>
                        </div>

                        <div class="form-group">
                            <label for="postal_code">Kode Pos</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>

                        <div class="form-group">
                            <label for="address_line">Alamat Lengkap</label>
                            <textarea class="form-control" id="address_line" name="address_line" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="duration_months">Durasi Tabungan (bulan)</label>
                            <input type="number" class="form-control" id="duration_months" name="duration_months" required>
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
