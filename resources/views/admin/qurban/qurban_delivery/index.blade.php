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
                <a href="#">Aktivitas</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Data Pengiriman</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Pengiriman Qurban</h4>
                        <a href="{{ route('delivery.create') }}" class="btn btn-primary btn-round ms-auto">
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
                                    <th>Pelanggan</th>
                                    <th>Ternak</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($deliveries as $delivery)
                                    <tr>
                                        <td>{{ $delivery->pelanggan }}</td>
                                        <td>{{ $delivery->ternak }}</td>
                                        <td>{{ $delivery->jumlah }} Ekor</td>
                                        <td>{{ $delivery->tanggal }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning">Ubah</button>
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>Pelanggan 1</td>
                                        <td>Sapi 1</td>
                                        <td>2 Ekor</td>
                                        <td>2025-04-28</td>
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
