@extends('layouts.auth.index')

@section('content')
    <div class="container container-signup animated fadeIn d-block">
        <h3 class="text-center">Sign Up</h3>
        <div class="login-form">
          <div class="form-sub">
            <div class="form-floating form-floating-custom mb-3">
              <input
                id="fullname"
                name="fullname"
                type="text"
                class="form-control"
				placeholder="fullname"
                required
              />
              <label for="fullname">Fullname</label>
            </div>
            <div class="form-floating form-floating-custom mb-3">
              <input
                id="email"
                name="email"
                type="email"
                class="form-control"
				placeholder="email"
                required
              />
              <label for="email">Email</label>
            </div>
            <div class="form-floating form-floating-custom mb-3">
              <input
                id="passwordsignin"
                name="passwordsignin"
                type="password"
                class="form-control"
				placeholder="passwordsignin"
                required
              />
              <label for="passwordsignin">Password</label>
              <div class="show-password">
                <i class="icon-eye"></i>
              </div>
            </div>
            <div class="form-floating form-floating-custom mb-3">
              <input
                id="confirmpassword"
                name="confirmpassword"
                type="password"
                class="form-control"
				placeholder="confirmpassword"
                required
              />
              <label for="confirmpassword">Confirm Password</label>
              <div class="show-password">
                <i class="icon-eye"></i>
              </div>
            </div>
          </div>
          <!-- <div class="row form-sub m-0">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" name="agree" id="agree" />
              <label class="form-check-label" for="agree"
                >I Agree the terms and conditions.</label
              >
            </div>
          </div> -->
          <div class="form-action">
            <!-- <a href="#" id="show-signin" class="btn btn-danger btn-link btn-login me-3">Cancel</a> -->
            <a href="#" class="btn btn-primary btn-login w-100">Sign Up</a>
          </div>
          <div class="login-account">
                <span class="msg">Sudah punya akun ?</span>
                <a href="{{ url('auth/login') }}" id="show-signup" class="link">Sign In</a>
            <!-- <a href="#" id="show-signup" class="link">Sign Up</a> -->
            </div>
        </div>
    </div>
@endsection
