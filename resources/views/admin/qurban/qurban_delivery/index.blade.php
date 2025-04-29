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
                <a href="#">Pengiriman Qurban</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Pengiriman Qurban</h4>
                        <a href="{{ route('qurban_delivery.create') }}" class="btn btn-primary btn-round ms-auto">
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
                                    <th>Nomor Hewan</th>
                                    <th>Nama Pengemudi</th>
                                    <th>Waktu Berangkat</th>
                                    <th>Waktu Sampai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deliveries as $delivery)
                                    <tr>
                                        <td>{{ $delivery->animal_number }}</td>
                                        <td>{{ $delivery->driver_name }}</td>
                                        <td>{{ $delivery->departure_time }}</td>
                                        <td>{{ $delivery->arrival_time ?? '-' }}</td>
                                        <td>{{ $delivery->status }}</td>
                                        <td>
                                            <a href="{{ route('delivery.edit', $delivery->id) }}" class="btn btn-sm btn-warning">Ubah</a>
                                            <form action="{{ route('delivery.destroy', $delivery->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Dummy Data -->
                                    <tr>
                                        <td>QBN-001</td>
                                        <td>Bagus Setiawan</td>
                                        <td>2025-04-29 06:00</td>
                                        <td>2025-04-29 09:20</td>
                                        <td>Terkirim</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning">Ubah</a>
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>QBN-002</td>
                                        <td>Siti Maesaroh</td>
                                        <td>2025-04-29 07:15</td>
                                        <td>-</td>
                                        <td>Dalam Perjalanan</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning">Ubah</a>
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
