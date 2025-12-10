<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Cek ID
if (!isset($_GET['id'])) {
    die("ID fasilitas tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data fasilitas
$query = "SELECT id, nama_fasilitas, deskripsi, gambar, kategori, created_at
          FROM fasilitas 
          WHERE id = $id";

$result = pg_query($conn, $query);
$data = pg_fetch_assoc($result);

if (!$data) {
    die("Fasilitas tidak ditemukan.");
}
?>

<style>
    /* Wrapper Utama */
    .fasilitas-wrapper {
        background-color: #f8f9fa;
        padding-top: 50px;
        padding-bottom: 80px;
        min-height: 100vh;
    }

    /* Card Detail */
    .detail-card {
        max-width: 900px;
        margin: auto;
        border-radius: 20px;
        background: #fff;
        border: none;
        overflow: hidden;
        position: relative;
    }

    /* Gambar Utama */
    .detail-image-container {
        width: 100%;
        height: 450px;
        background-color: #eee;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }
    .detail-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Konten Body */
    .detail-content {
        padding: 40px 50px;
    }

    /* Judul Besar */
    .detail-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 10px;
        line-height: 1.3;
        color: #2c3e50;
    }

    /* Badge Kategori */
    .category-badge {
        background-color: #eef2f7;
        color: #0d6efd;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 20px;
    }

    /* Isi Deskripsi */
    .detail-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #444;
        text-align: justify;
        margin-top: 10px;
        
        /* Mencegah teks panjang merusak layout */
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
    }

    /* Tombol Kembali */
    .back-btn {
        margin-top: 40px;
        display: inline-flex;
        align-items: center;
        padding: 12px 30px;
        border-radius: 50px;
        background: #0d6efd;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
    }
    .back-btn:hover {
        background: #0b59d1;
        transform: translateY(-3px);
        color: #fff;
    }

    @media(max-width: 768px) {
        .detail-image-container { height: 250px; }
        .detail-content { padding: 25px; }
    }
</style>

<section class="hero-section text-center" style="background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); padding: 80px 0; margin-bottom: 0; color: white;">
    <div class="container">
        <h1 class="fw-bold display-5">Fasilitas Laboratorium</h1>
        <p class="lead opacity-75 mt-2">Sarana dan prasarana pendukung riset di Lab AI.</p>
    </div>
</section>

<div class="fasilitas-wrapper">
    <div class="container">
        
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">Detail <span class="text-primary">Fasilitas</span></h2>
        </div>
        
        <div class="detail-card shadow-lg">

            <div class="detail-image-container">
                <img src="../assets/uploads/fasilitas/<?= htmlspecialchars($data['gambar']) ?>" 
                     alt="<?= htmlspecialchars($data['nama_fasilitas']) ?>">
            </div>

            <div class="detail-content">
                
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <span class="category-badge">
                        <i class="bi bi-tag-fill me-1"></i> <?= htmlspecialchars($data['kategori']) ?>
                    </span>
                    
                    <span class="text-muted small">
                        <i class="bi bi-calendar-check me-1"></i> Ditambahkan: <?= date('d F Y', strtotime($data['created_at'])) ?>
                    </span>
                </div>

                <h1 class="detail-title"><?= htmlspecialchars($data['nama_fasilitas']) ?></h1>

                <hr class="text-muted opacity-25">

                <h5 class="fw-bold mb-3">Deskripsi / Spesifikasi</h5>

                <div class="detail-body">
                    <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                </div>

                <div class="text-center">
                    <a href="fasilitas.php" class="back-btn">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Fasilitas
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>