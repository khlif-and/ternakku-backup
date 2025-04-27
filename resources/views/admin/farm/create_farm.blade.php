@extends('layouts.auth.index')

@section('content')
    <div class="container container-login animated fadeIn d-block">
        <h3 class="text-center">Create Farm</h3>

        <div class="login-form">
            <form action="{{ url('create-farm') }}" method="POST">
                @csrf

                <div class="form-sub">
                    <!-- Input Farm Name -->
                    <div class="form-floating form-floating-custom mb-3">
                        <input
                            id="farm_name"
                            name="farm_name"
                            type="text"
                            class="form-control"
                            placeholder="Nama Peternakan"
                            required
                        />
                        <label for="farm_name">Nama Peternakan</label>
                    </div>

                    <!-- Input Registration Date -->
                    <div class="form-floating form-floating-custom mb-3">
                        <input
                            id="registration_date"
                            name="registration_date"
                            type="date"
                            class="form-control"
                            required
                        />
                        <label for="registration_date">Tanggal Registrasi</label>
                    </div>

                    <!-- Input Qurban Partner -->
                    <div class="form-check mb-3">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="qurban_partner"
                            id="qurban_partner"
                            value="1"
                        >
                        <label class="form-check-label" for="qurban_partner">
                            Qurban Partner
                        </label>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="form-action mb-3">
                    <button type="submit" class="btn w-100 btn-login" style="background-color: #6CC3A0">Create Farm</button>
                </div>
            </form>


            <div class="text-center mt-3">
                <a href="{{ url('select-farm') }}" class="btn btn-outline-primary">
                    Pilih Farm yang Sudah Ada
                </a>
            </div>
        </div>
    </div>
@endsection
