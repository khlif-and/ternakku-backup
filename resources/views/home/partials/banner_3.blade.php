<div class="container-fluid py-5">
    <div class="row align-items-center">
        <!-- Bagian teks -->
        <div class="col-md-6 text-section">
            <small class="text-muted">Ga perlu repot untuk bertenak </small>
            <h1 class="display-4 fw-bold">Karena TernakKu akan selalu membantuMu</h1>
            <p class="lead text-success">Ga perlu repot untuk atur ini itu,
                kita bakalan ingetin kamu untuk jadwal threatment
                semua ternak kamu, Dengan ternakKu saatnya peternak
                rakyat Go digital!!</p>
            <a href="#" class="btn custom-btn">Jelajahi Sekarang</a>
        </div>

        <!-- Bagian gambar -->
        <div class="col-md-6 text-center">
            <img src="{{ asset('home/assets/img/banner_cow_3.png') }}" alt="Peternakan" class="img-fluid banner-image">
        </div>
    </div>
    <!-- Garis setelah seluruh konten -->
    <hr class="custom-hr">
</div>

<style>
    /* Pengaturan gambar */
    .banner-image {
        max-width: 90%;
        height: auto;
        object-fit: cover;
        transform: scale(1);
    }

    /* Pengaturan teks default untuk laptop */
    .text-section {
        padding-left: 50px;
        padding-top: 30px;
    }

    /* Custom button styling */
    .custom-btn {
        font-size: 14px;
        padding: 10px 20px;
        background-color: #00e676;
        color: white;
        border: none;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .custom-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: rgba(255, 255, 255, 0.3);
        transition: all 0.75s ease;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        z-index: 1;
    }

    .custom-btn:hover::before {
        transform: translate(-50%, -50%) scale(1);
    }

    .custom-btn:hover {
        background-color: #00c853;
        color: #ffffff;
        box-shadow: 0 10px 20px rgba(0, 200, 83, 0.4);
        transform: translateY(-3px);
    }

    /* Garis custom */
    .custom-hr {
        border: none;
        height: 2px;
        background: linear-gradient(to right, #36343471, #24252579);
        margin-top: 40px;
        width: 100%;
    }

    /* Responsif untuk layar mobile dan tablet */
    @media (max-width: 768px) {
        .row {
            flex-direction: column;
            /* Ubah susunan kolom menjadi kolom untuk mobile */
        }

        .banner-image {
            max-width: 100%;
            margin-top: 90px;
            /* Tambahkan jarak untuk gambar di layar kecil */
        }

        .text-section {
            padding-left: 20px;
            padding-right: 20px;
            text-align: center;
        }
    }

    /* Responsif untuk layar monitor yang lebih besar */
    @media (min-width: 1600px) {
        .banner-image {
            max-width: 200%;
            transform: scale(1.5);
        }

        .text-section {
            padding-left: 200px;
            padding-top: 50px;
        }
    }
</style>
