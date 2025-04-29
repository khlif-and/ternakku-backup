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
                <a href="#">Pelacakan Armada</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Pelacakan Armada</h4>
                        <a href="{{ route('fleet_tracking.create') }}" class="btn btn-primary btn-round ms-auto">
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
                                    <th>Nama Pengemudi</th>
                                    <th>Nama Kendaraan</th>
                                    <th>Waktu Keberangkatan</th>
                                    <th>Lokasi Terakhir Diketahui</th>
                                    <th>Tanggal Pelacakan</th>
                                    <th>Status Armada</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (!empty($fleetTrackings) && count($fleetTrackings) > 0)
                                    @foreach ($fleetTrackings as $fleet)
                                        <tr>
                                            <td>{{ $fleet['driver_name'] ?? '-' }}</td>
                                            <td>{{ $fleet['vehicle'] ?? '-' }}</td>
                                            <td>{{ $fleet['departure_time'] ?? '-' }}</td>
                                            <td>{{ $fleet['last_location'] ?? '-' }}</td>
                                            <td>{{ $fleet['tracking_date'] ?? '-' }}</td>
                                            <td>{{ $fleet['status'] ?? '-' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">Ubah</button>
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>Anton</td>
                                        <td>Truk 01</td>
                                        <td>2025-04-28 07:45</td>
                                        <td>Jalan Raya Jakarta</td>
                                        <td>2025-04-28</td>
                                        <td>Dalam Perjalanan</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning">Ubah</button>
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                @endif
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
