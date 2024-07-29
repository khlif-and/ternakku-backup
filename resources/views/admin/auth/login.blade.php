@extends('layouts.auth.index')

@section('content')
    <div class="container container-login animated fadeIn d-block">
        <h3 class="text-center">Sign In</h3>
        <div class="login-form">
            <div class="form-sub">
                <div class="form-floating form-floating-custom mb-3">
                    <input
                        id="username"
                        name="username"
                        type="text"
                        class="form-control"
                        placeholder="username"
                        required
                    />
                    <label for="username">Username</label>
                </div>
                <div class="form-floating form-floating-custom mb-3">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control"
                        placeholder="password"
                        required
                    />
                    <label for="password">Password</label>
                    <div class="show-password">
                        <i class="icon-eye"></i>
                    </div>
                </div>
            </div>
            <div class="row m-0">
            <div class="d-flex form-sub">
                <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberme" />
                <label class="form-check-label" for="rememberme">Remember Me</label>
                </div>

                <!-- <a href="#" class="link float-end">Forget Password ?</a> -->
            </div>
            </div>
            <div class="form-action mb-3">
            <a href="#" class="btn btn-primary w-100 btn-login">Sign In</a>
            </div>
            <div class="login-account">
                <span class="msg">Belum punya akun ?</span>
                <a href="{{ url('auth/register') }}" id="show-signup" class="link">Sign Up</a>
            <!-- <a href="#" id="show-signup" class="link">Sign Up</a> -->
            </div>
        </div>
    </div>
@endsection
