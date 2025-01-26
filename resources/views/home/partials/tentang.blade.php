<div class="container py-5" style="margin-top: -70px;">
    <!-- Bagian judul Tentang Kami -->
    <div class="row mb-4">
        <div class="col text-center">
            <h2 class="fw-bold">{{ $tentangKami['judul'] }}</h2>
            <p class="lead">
                {{ $tentangKami['deskripsi'] }}
            </p>
        </div>
    </div>

    <!-- Bagian Card yang berada di bawah teks -->
    <div class="row justify-content-center">
        @foreach ($tentangKami['cards'] as $card)
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm p-4">
                <div class="card-body">
                    <div class="card-img-placeholder">
                        <img src="{{ asset($card['img']) }}" alt="Card Image" class="img-fluid rounded">
                    </div>
                    <!-- Teks dipindahkan ke bawah gambar -->
                    <p class="card-text mt-3">{{ $card['content'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
        <p class="lead">
            {{ $tentangKami['deskripsi_2'] }}
        </p>
    </div>
</div>


