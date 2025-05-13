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
                <span class="navbar-brand"
                    style="font: 400 40px 'Oleo Script', Helvetica, sans-serif; color: white; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);">
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
                <li
                    class="nav-item {{ Request::is('qurban/farm*') || Request::is('qurban/customer*') || Request::is('qurban/fleet') || Request::is('qurban/fleet/*') || Request::is('qurban/driver*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dataAwal"
                        class="{{ Request::is('qurban/farm*') || Request::is('qurban/customer*') || Request::is('qurban/fleet') || Request::is('qurban/fleet/*') || Request::is('qurban/driver*') ? '' : 'collapsed' }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Data Awal</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ Request::is('qurban/farm*', 'qurban/customer*', 'qurban/fleet*', 'qurban/driver*') ? 'show' : '' }}"
                        id="dataAwal">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('qurban/farm/user-list*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/farm/user-list') }}"><span class="sub-item">Data
                                        Pengguna</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/customer*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/customer') }}"><span class="sub-item">Data Pelanggan & Alamat
                                        Kirim</span></a>
                            </li>
                            <li
                                class="{{ Request::is('qurban/fleet') || Request::is('qurban/fleet/*') ? 'active' : '' }}">

                                <a href="{{ url('qurban/fleet') }}"><span class="sub-item">Data Armada</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/driver*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/driver') }}"><span class="sub-item">Data Pengemudi</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu Aktivitas -->
                <li
                    class="nav-item
    {{ Request::is('qurban/sales-order') ||
    Request::is('qurban/sales-order/*') ||
    Request::is('qurban/sales-livestock') ||
    Request::is('qurban/sales-livestock/*') ||
    Request::is('qurban/reweight') ||
    Request::is('qurban/reweight/*') ||
    Request::is('qurban/payment') ||
    Request::is('qurban/payment/*') ||
    Request::is('qurban/delivery') ||
    Request::is('qurban/delivery/*') ||
    Request::is('qurban/fleet-tracking') ||
    Request::is('qurban/livestock-delivery-note*') ||
    Request::is('qurban/fleet-tracking/*') ||
    Request::is('qurban/qurban-delivery-order-data*')
        ? 'active'
        : '' }}">

                    <a data-bs-toggle="collapse" href="#aktivitas"
                        class="
       {{ Request::is('qurban/sales-order') ||
       Request::is('qurban/sales-order/*') ||
       Request::is('qurban/sales-livestock') ||
       Request::is('qurban/sales-livestock/*') ||
       Request::is('qurban/reweight') ||
       Request::is('qurban/reweight/*') ||
       Request::is('qurban/payment') ||
       Request::is('qurban/payment/*') ||
       Request::is('qurban/delivery') ||
       Request::is('qurban/delivery/*') ||
       Request::is('qurban/fleet-tracking') ||
       Request::is('qurban/livestock-delivery-note*') ||
       Request::is('qurban/fleet-tracking/*') ||
       Request::is('qurban/qurban-delivery-order-data*')
           ? ''
           : 'collapsed' }}">

                        <i class="fas fa-tasks"></i>
                        <p>Aktivitas</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ Request::is('qurban/sales-order*', 'qurban/sales-livestock*', 'qurban/reweight*', 'qurban/payment*', 'qurban/delivery*', 'qurban/fleet-tracking*') ? 'show' : '' }}"
                        id="aktivitas">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('qurban/reweight*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/reweight') }}"><span class="sub-item">ReWeight / Timbang
                                        Ulang</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/sales-order*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/sales-order') }}"><span class="sub-item">Sales Order
                                        Kurban</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/sales-livestock*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/sales-livestock') }}"><span class="sub-item">Penjualan Ternak
                                        Kurban</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/payment*') ? 'active' : '' }}">
                                <a href="#"><span class="sub-item">Penerimaan Pembayaran</span></a>
                            </li>
                            <li
                                class="{{ Request::is('qurban/livestock-delivery-note*') || Request::is('qurban/livestock-delivery-note*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/livestock-delivery-note') }}">
                                    <span class="sub-item">Surat Jalan Ternak Kurban</span>
                                </a>
                            </li>

                            <li
                                class="{{ Request::is('qurban/delivery*') || Request::is('qurban/delivery*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/delivery') }}">
                                    <span class="sub-item">Pengiriman Ternak Kurban</span>
                                </a>
                            </li>




                            <li
                                class="{{ Request::is('qurban/fleet-tracking') || Request::is('qurban/fleet-tracking/*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/fleet-tracking') }}">
                                    <span class="sub-item">Pelacakan Armada</span>
                                </a>
                            </li>

                            <li class="{{ Request::is('qurban/payment*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/payment') }}"><span class="sub-item">Pembayaran</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/qurban-delivery-order-data*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/qurban-delivery-order-data') }}">
                                    <span class="sub-item">Surat Jalan Qurban</span>
                                </a>
                            </li>

                            <li class="{{ Request::is('qurban/sales-qurban*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/sales-qurban') }}"><span class="sub-item">Sales
                                        Qurban</span></a>
                            </li>
                            <li class="{{ Request::is('qurban/cancelation-qurban*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/cancelation-qurban') }}"><span class="sub-item">Cancelation
                                        Qurban</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu Laporan -->
                <li class="nav-item {{ Request::is('qurban/report*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#laporanQurban"
                        class="{{ Request::is('qurban/report*') ? '' : 'collapsed' }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Laporan</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ Request::is('qurban/report*') ? 'show' : '' }}" id="laporanQurban">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('qurban/population-report*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/population-report') }}">
                                    <span class="sub-item">Laporan Populasi</span>
                                </a>
                            </li>



                            <li class="{{ Request::is('qurban/report/sales-order*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/report/sales-order') }}">
                                    <span class="sub-item">Daftar Sales Order</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('qurban/report/sales-livestock*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/report/sales-livestock') }}">
                                    <span class="sub-item">Daftar Penjualan Hewan Kurban</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('qurban/report/payment*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/report/payment') }}">
                                    <span class="sub-item">Daftar Penerimaan Pembayaran</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('qurban/report/cancelation*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/report/cancelation') }}">
                                    <span class="sub-item">Daftar Pembatalan Penjualan</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('qurban/report/surat-jalan*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/report/surat-jalan') }}">
                                    <span class="sub-item">Daftar Surat Jalan</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('qurban/report/delivery*') ? 'active' : '' }}">
                                <a href="{{ url('qurban/report/delivery') }}">
                         