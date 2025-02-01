@extends('layouts.auth.index')

@section('content')
    <div class="container container-login animated fadeIn d-block">
        <h3 class="text-center">Sign In</h3>

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
            <form action="{{ url('auth/login') }}" method="POST">
                @csrf
                <div class="form-sub">
                    <!-- Input Username -->
                    <div class="form-floating form-floating-custom mb-3">
                        <input
                            id="username"
                            name="username"
                            type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="username"
                            value="{{ old('username') }}"
                            required
                        />
                        <label for="username">Username</label>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div class="form-floating form-floating-custom mb-3">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="password"
                            required
                        />
                        <label for="password">Password</label>
                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="row m-0">
                    <div class="d-flex form-sub">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="rememberme"
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            />
                            <label class="form-check-label" for="rememberme">Remember Me</label>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="form-action mb-3">
                    <button type="submit" class="btn w-100 btn-login" style="background-color: #6CC3A0">Log In</button>
                </div>
            </form>

            <!-- Akun Belum Terdaftar -->
            <div class="login-account">
                <span class="msg">Belum punya akun?</span>
                <a href="{{ url('auth/register') }}" id="show-signup" class="link">Sign Up</a>
            </div>
        </div>
    </div>
@endsection
