<div id="main-header-wrapper" class="header-wrapper d-flex">
    {{-- Logo + toggle --}}
    {{-- layouts.qurban.sidebar --}}
    <div class="sidebar-header px-3 py-2 d-flex align-items-center justify-content-between"
        style="background-color: #1b2230;">
        <a href="{{ url('qurban/dashboard') }}">
            <img src="{{ asset('admin/img/img_ternakku.jpg') }}" alt="brand" height="20">
        </a>
        <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-left text-white"></i>
        </button>
    </div>


    {{-- Navbar kanan --}}
    <nav class="navbar navbar-header navbar-expand-lg flex-grow-1 justify-content-end px-3">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">
                        <div class="avatar-sm">
                            <img src="{{ asset('admin/img/profile.jpg') }}" alt="..."
                                class="avatar-img rounded-circle">
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold" style="color: #255F38;">
                                {{ explode(' ', auth()->user()->name)[0] }}
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="{{ asset('admin/img/profile.jpg') }}" alt="image profile"
                                            class="avatar-img rounded">
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ auth()->user()->name }}</h4>
                                        <p class="text-muted">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#logoutModal">Logout</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
