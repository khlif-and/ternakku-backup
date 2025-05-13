@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Laporan Populasi</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="#"><i class="icon-home"></i></a>
            </li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Laporan</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Laporan Populasi</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Populasi Ternak</h4>
                        <a href="{{ route('population-report.create') }}" class="btn btn-primary btn-round ms-auto">
                            <i class="fa fa-plus"></i>
                            Tambah Data Populasi
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Jenis Ternak</th>
                                    <th>Jumlah</th>
                                    <th>Lokasi</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($populations as $data)
                                    <tr>
                                        <td>{{ $data->livestock_type ?? '-' }}</td>
                                        <td>{{ $data->quantity }} Ekor</td>
                                        <td>{{ $data->location ?? '-' }}</td>
                                        <td>{{ $data->notes ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('population-report.edit', $data->id) }}" class="btn btn-sm btn-warning">Ubah</a>
                                            <form action="{{ route('population-report.destroy', $data->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data populasi ternak.</td>
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
    $(document).ready(function () {
        $('#basic-datatables').DataTable();
    });
</script>
@endsection
