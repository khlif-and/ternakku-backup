<style>
    .sub-item {
        font-size: 12px !important;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Oleo+Script&display=swap" rel="stylesheet">

<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('qurban/dashboard') }}" class="logo">
                <span class="navbar-brand" style="font: 400 40px 'Oleo Script', Helvetica, sans-serif; color: white; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);">
                    Ternakku
                </span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- Menu Data Awal -->
                <li class="nav-item {{ Request::is('qurban/farm*', 'qurban/customer*', 'qurban/fleet*', 'qurban/driver*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dataAwal" class="{{ Request::is('qurban/farm*', 'qurban/customer*', 'qurban/fleet*', 'qurban/driver*') ? '' : 'collapsed' }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Data Awal</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('qurban/farm*', 'qurban/customer*', 'qurban/fleet*', 'qurban/driver*') ? 'show' : '' }}" id="dataAwal">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('qurban/farm/user-list*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/farm/user-list') }}"><span class="sub-item">Data Pengguna</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/customer*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/customer') }}"><span class="sub-item">Data Pelanggan & Alamat Kirim</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/fleet*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/fleet') }}"><span class="sub-item">Data Armada</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/driver*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/driver') }}"><span class="sub-item">Data Pengemudi</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu Aktivitas -->
                <li class="nav-item {{ Request::is('qurban/sales-order*', 'qurban/reweight*', 'qurban/payment*', 'qurban/delivery*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#aktivitas" class="{{ Request::is('qurban/sales-order*', 'qurban/reweight*', 'qurban/payment*', 'qurban/delivery*') ? '' : 'collapsed' }}">
                        <i class="fas fa-tasks"></i>
                        <p>Aktivitas</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('qurban/sales-order*', 'qurban/reweight*', 'qurban/payment*', 'qurban/delivery*') ? 'show' : '' }}" id="aktivitas">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('qurban/reweight*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/reweight') }}"><span class="sub-item">ReWeight / Timbang Ulang</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/sales-order*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/sales-order') }}"><span class="sub-item">Sales Order Kurban</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/sales*') ? 'active' : '' }}">
                                <a href="#"><span class="sub-item">Penjualan Ternak Kurban</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/payment*') ? 'active' : '' }}">
                                <a href="#"><span class="sub-item">Penerimaan Pembayaran</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/delivery/surat-jalan*') ? 'active' : '' }}">
                                <a href="#"><span class="sub-item">Surat Jalan Ternak</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/delivery*') ? 'active' : '' }}">
                                <a href="#"><span class="sub-item">Pengiriman Ternak Kurban</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/delivery/tracking*') ? 'active' : '' }}">
                                <a href="#"><span class="sub-item">Lacak Armada Pengiriman</span></a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
