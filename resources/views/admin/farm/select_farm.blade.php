@extends('layouts.auth.index')

@section('content')
    <div class="container container-login animated fadeIn d-block">
        <h3 class="text-center">Select Farm</h3>

        <!-- Menampilkan pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Menampilkan pesan error -->
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="mb-4 d-flex justify-content-center">
                    <button type="button" class="btn btn-danger" id="alert_demo_3_2"> {{ $error }}</button>
                </div>
            @endforeach
        @endif

        <div class="login-form">
            <form action="{{ url('select-farm') }}" method="POST">
                @csrf
                <div class="form-sub">
                    <!-- Input Farm -->
                    <div class="form-floating form-floating-custom mb-3">
                        <select id="farm_id" required name="farm_id"
                            class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                            <option value="" selected disabled>Select Farm</option>
                            @foreach ($farms as $farm)
                                <option value="{{ $farm->farm_id }}">{{ $farm->farm->name }}</option>
                            @endforeach
                        </select>

                        <label for="farm_id">Select Farm</label>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <input type="hidden" name="redirect_url" value="{{ request()->query('redirect_url') }}">

                <!-- Tombol Submit -->
                <div class="form-action mb-3">
                    <button type="submit" class="btn w-100 btn-login" style="background-color:
                </div>
            </form>

            <!-- Tambahan Tombol Create Farm -->
            <div class="form-action">
                <a href="{{ route('farm.create') }}" class="btn w-100 btn-outline-primary">
                    Create New Farm
                </a>
            </div>
        </div>
    </div>
@endsection
