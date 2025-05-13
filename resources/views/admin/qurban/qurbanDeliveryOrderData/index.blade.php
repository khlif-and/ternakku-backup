@extends('layouts.qurban.index')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Surat Jalan Qurban</h3>
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
                    <a href="#">Surat Jalan Qurban</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Surat Jalan Qurban</h4>
                            <a href="{{ route('qurban-delivery-order-data.create') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Surat Jalan
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Penerima</th>
                                        <th>Jenis Ternak</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Pengiriman</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($qurbanDeliveryOrderData as $data)
                                        <tr>
                                            <td>{{ $data->recipient_name ?? '-' }}</td>
                                            <td>{{ $data->livestock_type ?? '-' }}</td>
                                            <td>{{ $data->quantity }} Ekor</td>
                                            <td>{{ \Carbon\Carbon::parse($data->delivery_date)->format('d M Y') }}</td>
                                            <td>{{ $data->notes ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('qurban-delivery-order-data.edit', $data->id) }}" class="btn btn-sm btn-warning">Ubah</a>
                                                <form action="{{ route('qurban-delivery-order-data.destroy', $data->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada data surat jalan qurban.</td>
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
