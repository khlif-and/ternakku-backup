<div class="container-fluid py-5" style="padding-top: 0;">
    <div class="row align-items-start"> <!-- Mengubah align-items agar konten berada di atas -->
        <!-- Bagian gambar -->
        <div class="col-md-6 text-center">
            <img src="{{ asset('home/assets/img/banner_cow_2.png') }}" alt="Peternakan" class="img-fluid banner-image">
        </div>

        <!-- Bagian teks -->
        <div class="col-md-6 text-section">
            <small class="text-muted">Ternakku Untuk Indonesia Hebat</small>
            <h1 class="display-4 fw-bold">Mulai Lacak Setiap Ternak Dengan Kemudahan Dari TernakKu</h1>
            <p class="lead text-success">Dengan kemudahan teknologi yang kami hadirkan, setiap peternak bisa dengan mudah melacak, mengontrol, bahkan merawat ternak sapi dengan mudah. Kami ingin setiap peternak memiliki kemudahan akses yang cepat, inovatif, dan membantu.</p>
            <a href="#" class="btn btn-custom">Mulai Sekarang, Yuk</a>
        </div>
    </div>
    <!-- Garis setelah seluruh konten -->
    <hr class="custom-hr">
</div>

<!-- Style -->
<style>
/* Pengaturan gambar */
.banner-image {
    max-width: 150%;
    height: auto;
    object-fit: cover;
    transform: scale(1.2); /* Ukuran gambar sedikit lebih kecil */
    margin-bottom: 0; /* Menghapus margin bawah */
}

/* Pengaturan teks */
.text-section {
    padding-left: 20px;
    padding-top: 0; /* Menghilangkan padding atas */
    margin-top: 0; /* Menghilangkan margin atas */
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
    margin-top: 20px; /* Kurangi margin atas */
    width: 100%;
}

.text-success {
    font-size: 16px;
    color: gray !important;
}

/* Responsif untuk layar mobile dan tablet */
@media (max-width: 768px) {
    .row {
        flex-direction: column-reverse; /* Gambar di bawah teks pada mobile */
    }

    .banner-image {
        max-width: 100%;
        margin-top: 10px; /* Tambah sedikit jarak pada mobile */
    }

    .text-section {
        padding-left: 20px;
        padding-right: 20px;
        text-align: center;
        margin-top: 0; /* Hilangkan margin atas di mobile */
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
