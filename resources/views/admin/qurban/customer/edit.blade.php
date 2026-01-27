@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Data Pelanggan</h3>
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
                <a href="#">Data Pelanggan</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Edit Data Pelanggan</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('qurban/customer/' . $customer->id ) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="mb-4">
                            <x-form.input 
                                name="name" 
                                label="Nama" 
                                :value="$customer->name"
                                required 
                                error="name"
                            />
                        </div>
                        <div class="mb-8">
                            <x-form.input 
                                name="phone_number" 
                                label="Nomor Telepon" 
                                :value="$customer->phone_number"
                                required 
                                error="phone_number"
                            />
                        </div>
                        <div class="flex justify-start">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-6 py-2 transition-all">Simpan</button>
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
