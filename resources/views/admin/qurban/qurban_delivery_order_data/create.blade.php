@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Qurban Delivery Order Data</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ url('/') }}">
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
                <a href="{{ url('qurban/qurban-delivery-order-data') }}">Qurban Delivery Order Data</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Qurban Delivery Order</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="number">Delivery Order Number</label>
                            <input type="text" class="form-control" id="number" name="number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="farmer_name">Farmer Name</label>
                            <input type="text" class="form-control" id="farmer_name" name="farmer_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="destination">Destination</label>
                            <input type="text" class="form-control" id="destination" name="destination" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="animal_count">Animal Count</label>
                            <input type="number" class="form-control" id="animal_count" name="animal_count" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="note">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
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
        // Script tambahan kalau nanti butuh
    });
</script>
@endsection
