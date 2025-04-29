@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Data Pendaftaran Tabungan</h3>
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
                <a href="{{ route('contract.saving_registration.index') }}">Data Pendaftaran Tabungan</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Pendaftaran Tabungan</h4>
                        <a href="{{ route('contract.saving_registration.create') }}" class="btn btn-primary btn-round ms-auto">
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
                                    <th>Jenis Ternak</th>
                                    <th>Nama Ternak</th>
                                    <th>Peternakan</th>
                                    <th>Berat (kg)</th>
                                    <th>Harga per Kg (Rp)</th>
                                    <th>Harga Total (Rp)</th>
                                    <th>Provinsi</th>
                                    <th>Kabupaten/Kota</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan/Desa</th>
                                    <th>Kode Pos</th>
                                    <th>Alamat Lengkap</th>
                                    <th>Durasi Tabungan (Bulan)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($savingRegistrations as $saving)
                                    <tr>
                                        <td>{{ $saving->livestock_type_name }}</td>
                                        <td>{{ $saving->livestock_breed_name }}</td>
                                        <td>{{ $saving->farm_name }}</td>
                                        <td>{{ $saving->weight }} kg</td>
                                        <td>Rp {{ number_format($saving->price_per_kg, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($saving->price_total, 0, ',', '.') }}</td>
                                        <td>{{ $saving->province_name }}</td>
                                        <td>{{ $saving->regency_name }}</td>
                                        <td>{{ $saving->district_name }}</td>
                                        <td>{{ $saving->village_name }}</td>
                                        <td>{{ $saving->postal_code }}</td>
                                        <td>{{ $saving->address_line }}</td>
                                        <td>{{ $saving->duration_months }} bulan</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning">Ubah</button>
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>Sapi</td>
                                        <td>Bali</td>
                                        <td>CV. Silih Wangi Sawargi</td>
                                        <td>300</td>
                                        <td>Rp 85.000</td>
                                        <td>Rp 25.500.000</td>
                                        <td>JAWA BARAT</td>
                                        <td>KOTA BANDUNG</td>
                                        <td>COBLONG</td>
                                        <td>LEBAK SILIWANGI</td>
                                        <td>40132</td>
                                        <td>Masjid Albayyinah, Pelesiran, RT 02, RW 05</td>
                                        <td>10 bulan</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning">Ubah</button>
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
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
