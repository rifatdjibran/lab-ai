<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Ambil semua fasilitas
$query = "SELECT id, nama_fasilitas, deskripsi, gambar, kategori, created_at 
          FROM fasilitas 
          ORDER BY created_at DESC";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$fasilitasList = pg_fetch_all($result);
?>

<style>
    /* 1. Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 80px 0;
        margin-bottom: 2rem;
        color: white;
    }

    /* 2. Styling Card Fasilitas */
    .fasilitas-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: none;
        border-radius: 15px;
        background-color: #fff;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .fasilitas-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* Gambar Card */
    .fasilitas-card .card-img-top {
        height: 220px;
        object-fit: cover;
        border-bottom: 1px solid #f0f0f0;
    }

    /* Body Card */
    .fasilitas-card .card-body {
        display: flex;
        flex-direction: column;
        flex: 1; 
        padding: 1.5rem;
    }

    /* Judul */
    .card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3rem; /* Menjaga tinggi judul konsisten */
        margin-bottom: 0.8rem;
        font-weight: 700;
        color: #2c3e50;
    }

    /* Deskripsi - BAGIAN KUNCI PERUBAHAN */
    .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 0.95rem;
        color: #666;
        /* Magic CSS: margin-bottom: auto akan mendorong elemen dibawahnya ke dasar */
        margin-bottom: auto; 
        padding-bottom: 1rem;
    }

    /* Wrapper untuk Meta Data (Kategori & Tanggal) agar rapi */
    .meta-wrapper {
        border-top: 1px dashed #eee;
        padding-top: 15px;
        margin-top: 10px;
    }

    /* Info Row (Icon Hitam) */
    .info-row {
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 8px; /* Jarak antar info */
        display: flex;
        align-items: start;
    }
    .info-row i {
        margin-right: 8px;
        color: #000; 
        margin-top: 2px;
        width: 16px; /* Lebar fix agar teks rata kiri */
    }

    /* Tombol */
    .btn-detail {
        border-radius: 20px;
        font-weight: bold;
        margin-top: 10px;
    }
</style>

<section class="hero-section text-center">
    <div class="container">
        <h1 class="fw-bold display-5">Fasilitas Laboratorium</h1>
        <p class="lead opacity-75 mt-2">Sarana dan prasarana pendukung riset di Lab AI.</p>
    </div>
</section>

<section class="py-2">
    <div class="container">
        
        <h2 class="mb-4 text-center fw-bold" style="color: #333;">
            Daftar <span class="text-primary">Fasilitas</span>
        </h2>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php if ($fasilitasList): ?>
                <?php foreach ($fasilitasList as $f): ?>
                    <div class="col">
                        <div class="card fasilitas-card shadow-sm">

                            <img src="../assets/uploads/fasilitas/<?= htmlspecialchars($f['gambar']) ?>" 
                                 class="card-img-top"
                                 alt="<?= htmlspecialchars($f['nama_fasilitas']) ?>">

                            <div class="card-body">

                                <h5 class="card-title">
                                    <?= htmlspecialchars($f['nama_fasilitas']) ?>
                                </h5>

                                <p class="card-text">
                                    <?= mb_strimwidth(strip_tags($f['deskripsi']), 0, 110, "..."); ?>
                                </p>

                                <div class="meta-wrapper">
                                    <div class="info-row">
                                        <i class="bi bi-tag-fill"></i>
                                        <div><?= htmlspecialchars($f['kategori']) ?></div>
                                    </div>
                                    
                                    <div class="info-row">
                                        <i class="bi bi-calendar-check"></i>
                                        <div><?= date('d M Y', strtotime($f['created_at'])) ?></div>
                                    </div>

                                    <a href="detail_fasilitas.php?id=<?= $f['id'] ?>" 
                                       class="btn btn-outline-primary btn-sm w-100 btn-detail">
                                       Lihat Detail →
                                    </a>
                                </div>
                                </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="100" class="mb-3 opacity-50">
                    <h4 class="text-muted">Belum ada fasilitas yang tersedia.</h4>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>