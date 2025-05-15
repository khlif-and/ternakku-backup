<style>
    .sub-item {
        font-size: 12px !important;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Oleo+Script&display=swap" rel="stylesheet">

<div class="sidebar sidebar-style-2">
    <div class="sidebar-logo">
        <div class="logo-header">
            <div class="logo-container">
                <a href="{{ url('qurban/dashboard') }}" class="logo">
                    <span class="navbar-brand">{{ $farm->name }}</span>
                </a>
            </div>

            <!-- Divider Full Width Paksa -->
            <div class="divider-force"></div>

            <!-- Tombol opsional -->
            <div class="nav-toggle d-lg-none">
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
    </div>



    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- Menu Data Awal -->
                <li
                    class="nav-item {{ Request::is('qurban/farm*') || Request::is('qurban/customer*') || Request::is('qurban/fleet') || Request::is('qurban/fleet/*') || Request::is('qurban/driver*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dataAwal"
                        class="{{ Request::is('qurban/farm*') || Request::is('qurban/customer*') || Request::is('qurban/fleet') || Request::is('qurban/fleet/*') || Request::is('qurban/driver*') ? '' : 'collapsed' }}"
                        style="color: white !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                        <p style="color: white !important;">Data Awal</p>
                        <span class="caret" style="color: white !important;"></span>
                    </a>

                    <div class="collapse {{ Request::is('qurban/farm*', 'qurban/customer*', 'qurban/fleet*', 'qurban/driver*') ? 'show' : '' }}"
                        id="dataAwal">

                        <!-- Sub-menu box putih -->
                        <ul class="nav nav-collapse"
                            style="background-color: white; border-radius: 8px; padding: 10px; margin-top: 8px; list-style: none; padding-left: 0; text-align: left;">
                            <li style="list-style-type: none;">
                                <a href="{{ url('qurban/farm/user-list') }}"
                                    style="color: black !important; background-color: transparent !important; border: none !important; box-shadow: none !important; text-align: left !important;">
                                    <span class="sub-item"
                                        style="color: black !important; text-align: left !important;">Data
                                        Pengguna</span>
                                </a>
                            </li>
                            <li style="list-style-type: none;">
                                <a href="{{ url('qurban/customer') }}"
                                    style="color: black !important; background-color: transparent !important; border: none !important; box-shadow: none !important; text-align: left !important;">
                                    <span class="sub-item"
                                        style="color: black !important; text-align: left !important;">Data Pelanggan &
                                        Alamat Kirim</span>
                                </a>
                            </li>
                            <li style="list-style-type: none;">
                                <a href="{{ url('qurban/fleet') }}"
                                    style="color: black !important; background-color: transparent !important; border: none !important; box-shadow: none !important; text-align: left !important;">
                                    <span class="sub-item"
                                        style="color: black !important; text-align: left !important;">Data Armada</span>
                                </a>
                            </li>
                            <li style="list-style-type: none;">
                                <a href="{{ url('qurban/driver') }}"
                                    style="color: black !important; background-color: transparent !important; border: none !important; box-shadow: none !important; text-align: left !important;">
                                    <span class="sub-item"
                                        style="color: black !important; text-align: left !important;">Data
                                        Pengemudi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu Aktivitas -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#aktivitas"
                        class="{{ Request::is('qurban/sales-order') || Request::is('qurban/sales-order/*') || Request::is('qurban/sales-livestock') || Request::is('qurban/sales-livestock/*') || Request::is('qurban/reweight') || Request::is('qurban/reweight/*') || Request::is('qurban/payment') || Request::is('qurban/payment/*') || Request::is('qurban/delivery') || Request::is('qurban/delivery/*') || Request::is('qurban/fleet-tracking') || Request::is('qurban/livestock-delivery-note*') || Request::is('qurban/fleet-tracking/*') || Request::is('qurban/qurban-delivery-order-data*') ? '' : 'collapsed' }}"
                        style="color: white !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                        <p style="color: white !important;">Aktivitas</p>
                        <span class="caret" style="color: white !important;"></span>
                    </a>

                    <div class="collapse {{ Request::is('qurban/sales-order*', 'qurban/sales-livestock*', 'qurban/reweight*', 'qurban/payment*', 'qurban/delivery*', 'qurban/fleet-tracking*') ? 'show' : '' }}"
                        id="aktivitas">
                        <!-- Sub-menu dibungkus kotak putih -->
                        <ul class="nav nav-collapse"
                            style="background-color: white; border-radius: 8px; padding: 10px; margin-top: 8px;">
                            <li>
                                <a href="{{ url('qurban/reweight') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">ReWeight / Timbang
                                        Ulang</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/sales-order') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Sales Order Kurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/sales-livestock') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Penjualan Ternak
                                        Kurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Penerimaan Pembayaran</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/livestock-delivery-note') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Surat Jalan Ternak
                                        Kurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/delivery') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Pengiriman Ternak
                                        Kurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/fleet-tracking') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Pelacakan Armada</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/payment') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Pembayaran</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/qurban-delivery-order-data') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Surat Jalan Qurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/sales-qurban') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Sales Qurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/cancelation-qurban') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Cancelation Qurban</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Menu Laporan -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#laporanQurban"
                        class="{{ Request::is('qurban/report*') ? '' : 'collapsed' }}"
                        style="color: white !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                        <p style="color: white !important;">Laporan</p>
                        <span class="caret" style="color: white !important;"></span>
                    </a>

                    <div class="collapse {{ Request::is('qurban/report*') ? 'show' : '' }}" id="laporanQurban">
                        <!-- Kotak putih untuk submenu -->
                        <ul class="nav nav-collapse"
                            style="background-color: white; border-radius: 8px; padding: 10px; margin-top: 8px;">
                            <li>
                                <a href="{{ url('qurban/population-report') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Laporan Populasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/report/sales-order') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Daftar Sales Order</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/report/sales-livestock') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Daftar Penjualan Hewan
                                        Kurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/report/payment') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Daftar Penerimaan
                                        Pembayaran</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/report/cancelation') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Daftar Pembatalan
                                        Penjualan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/report/surat-jalan') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Daftar Surat Jalan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/report/delivery') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Daftar Pengiriman Hewan
                                        Kurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/report/antar') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Daftar Pengantaran Hewan
                                        Kurban</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('qurban/report/penerimaan') }}"
                                    style="color: black !important; background-color: transparent !important; box-shadow: none !important; border: none !important;">
                                    <span class="sub-item" style="color: black !important;">Daftar Penerimaan Hewan
                                        Kurban</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- ✅ Divider setelah Menu Laporan -->
                <div class="divider-force"></div>


                <!-- ✅ Tombol Panah dalam Lingkaran -->
                <div style="display: flex; justify-content: center; margin: 32px 0 16px 0;">
                    <button id="closeSidebarBtn"
                        style="
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.15);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    ">
                        <i class="fas fa-chevron-left" style="color: rgba(255, 255, 255, 0.7); font-size: 14px;"></i>
                    </button>
                </div>
            </ul>
        </div>
    </div>
</div>


<script>
    const toggleBtn = document.getElementById('closeSidebarBtn');
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;
    const icon = toggleBtn.querySelector('i');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('closed');
    body.classList.toggle('sidebar-closed');
    body.classList.toggle('sidebar-open'); // <-- tambahkan ini
    icon.classList.toggle('fa-chevron-left');
    icon.classList.toggle('fa-chevron-right');
});

</script>
