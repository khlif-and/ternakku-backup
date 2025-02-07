@extends('layouts.qurban.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h3 class="fw-bold mb-3">Data Pengguna</h3>
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
                <a href="{{ url('qurban/farm/user-list') }}">Data Pengguna</a>            
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Tambah Data Pengguna</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('qurban/farm/add-user') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="string" class="form-control" id="username" name="username" required>
                        </div>

                        <div id="user-info" class="d-none">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="string" class="form-control" id="name" name="name" readonly value="">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">No HP</label>
                                <input type="string" class="form-control" id="phone_number" name="phone_number" readonly value="">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="string" class="form-control" id="email" name="email" readonly value="">
                            </div>
                            <input type="hidden" id="user_id" name="user_id" value="">
                        </div>

                        <div class="form-group">
                            <label for="farm_role">Role</label>
                            <select class="form-control" id="farm_role" name="farm_role" required>
                                <option value="ADMIN">Admin</option>
                                <option value="ABK">ABK</option>
                                <option value="DRIVER">driver</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
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
$(document).ready(function() {
    var timer;

    $('#username').on('keyup', function() {
        clearTimeout(timer);

        var phoneNumber = $(this).val();

        timer = setTimeout(function() {
            $.ajax({
                url: '/qurban/farm/find-user',
                type: 'get',
                data: { username: phoneNumber },
                success: function(response) {

                    let data = response

                    $('#user-info').removeClass('d-none');
                    $('#name').val(data.name);
                    $('#phone_number').val(data.phone_number);
                    $('#email').val(data.email);
                    $('#user_id').val(data.id);

                    $('#.user-info').addClass('d-none');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }, 1000);
    });
});

</script>
@endsection
