@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Data Armada</h3>
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
                <a href="{{ url('qurban/fleet') }}">Data Armada</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Edit Data Armada</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('qurban/fleet/' . $fleet->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{$fleet->name}}">
                        </div>
                        <div class="form-group">
                            <label for="police_number">Nomor Polisi</label>
                            <input type="text" class="form-control" id="police_number" name="police_number" required value="{{$fleet->police_number}}">
                        </div>
                        <div class="form-group">
                            <label for="photo">Foto</label>
                            <div>
                                @if($fleet->photo)
                                <img src="{{ getNeoObject($fleet->photo) }}" alt="" style="width:200px; height:200px;" class="mb-4">
                                @endif
                            </div>
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
