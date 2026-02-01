@props(['regencyName', 'farmName', 'farmAddress'])

<div class="header">
    <h2>PEMERINTAH KABUPATEN/KOTA {{ $regencyName }}</h2>
    <h3>DINAS PERTANIAN DAN PETERNAKAN</h3>
    <h3>FARM {{ $farmName }}</h3>
    <p>{{ $farmAddress }}</p>
    <div class="header-line"></div>
</div>