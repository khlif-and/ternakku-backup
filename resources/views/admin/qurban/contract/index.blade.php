@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Data Kontrak Qurban</h3>
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
                <a href="#">Data Kontrak Qurban</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Kontrak Qurban</h4>
                        <a href="{{ route('contract.create') }}" class="btn btn-primary btn-round ms-auto">
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
                                    <th>Harga Total</th>
                                    <th>Provinsi</th>
                                    <th>Kabupaten/Kota</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan/Desa</th>
                                    <th>Alamat Lengkap</th>
                                    <th>Tanggal Kontrak</th>
                                    <th>Uang Muka</th>
                                    <th>Estimasi Tanggal Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($contracts as $contract)
                                    <tr>
                                        <td>{{ $contract->livestock_type_name }}</td>
                                        <td>{{ $contract->livestock_breed_name }}</td>
                                        <td>{{ $contract->farm_name }}</td>
                                        <td>{{ $contract->weight }} kg</td>
                                        <td>Rp {{ number_format($contract->price_total, 0, ',', '.') }}</td>
                                        <td>{{ $contract->province_name }}</td>
                                        <td>{{ $contract->regency_name }}</td>
                                        <td>{{ $contract->district_name }}</td>
                                        <td>{{ $contract->village_name }}</td>
                                        <td>{{ $contract->address_line }}</td>
                                        <td>{{ $contract->contract_date }}</td>
                                        <td>Rp {{ number_format($contract->down_payment, 0, ',', '.') }}</td>
                                        <td>{{ $contract->estimated_delivery_date }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success">Pendaftaran Tabungan</a>
                                            <button class="btn btn-sm btn-warning">Ubah</button>
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Dummy Data -->
                                    <tr>
                                        <td>Sapi</td>
                                        <td>Bali</td>
                                        <td>CV. Silih Wangi Sawargi</td>
                                        <td>300 kg</td>
                                        <td>Rp 25.500.000</td>
                                        <td>JAWA BARAT</td>
                                        <td>KOTA BANDUNG</td>
                                        <td>COBLONG</td>
                                        <td>LEBAK SILIWANGI</td>
                                        <td>Masjid Albayyinah, Pelesiran, RT 02, RW 05</td>
                                        <td>2024-08-11</td>
                                        <td>Rp 1.000.000</td>
                                        <td>2024-09-01</td>
                                        <td>
                                            <a href="{{ route('contract.saving_registration.index') }}" class="btn btn-sm btn-success">Pendaftaran Tabungan</a>
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
