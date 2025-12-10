<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    die("ID berita tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data berita berdasarkan ID
$query = "SELECT id, judul, isi, gambar, tanggal, penulis 
          FROM berita 
          WHERE id = $id";

$result = pg_query($conn, $query);
$data = pg_fetch_assoc($result);

if (!$data) {
    die("Berita tidak ditemukan.");
}
?>
<style>
/* Jarak biar nggak nempel ke navbar */
.news-wrapper {
    margin-top: 80px;
    margin-bottom: 80px;
}

/* ------------------------------ */
/* CARD DETAIL BERITA */
/* ------------------------------ */
.news-card {
    max-width: 950px;            /* lebih besar */
    margin: auto;
    border-radius: 18px;
    background: #fff;
    border: 1px solid #ddd;
    overflow: hidden;
    padding-bottom: 25px;
}

/* Gambar original, tidak di crop */
.news-image-container {
    width: 100%;
    padding: 25px 30px 10px;     /* jarak kiri kanan + atas */
    display: flex;
    justify-content: center;
}

.news-image-container img {
    width: auto;
    max-width: 100%;
    max-height: 550px;
    border-radius: 14px;
    object-fit: contain;         /* tampil sesuai ukuran asli */
}

/* Isi card */
.news-content {
    padding: 10px 35px 30px;
}

/* Judul */
.news-title {
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    margin-top: 10px;
}

/* Tanggal */
.news-date {
    text-align: center;
    color: #666;
    margin-top: 8px;
    font-size: 1rem;
}

/* Isi deskripsi */
.news-body {
    margin-top: 25px;
    font-size: 1.15rem;
    line-height: 1.8;
    color: #333;
}

/* Penulis */
.news-author {
    margin-top: 30px;
    font-size: 1rem;
    color: #444;
    font-weight: 600;
}

/* Tombol back */
.back-btn {
    margin-top: 40px;
    display: inline-block;
    padding: 10px 20px;
    border-radius: 10px;
    background: #0d6efd;
    color: #fff;
    text-decoration: none;
    transition: 0.2s ease;
}

/* Hover effect */
.back-btn:hover {
    background: #0b59d1;
    transform: translateX(-5px);
    color: #fff;
}

/* Responsif */
@media(max-width: 768px) {
    .news-card {
        max-width: 95%;
    }
    .news-content {
        padding: 20px;
    }
    .news-title {
        font-size: 1.6rem;
    }
}
</style>



<!-- Hero Section -->
<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Detail Berita</h1>
    </div>
</section>

<!-- Detail Berita -->
<div class="news-wrapper">
    <div class="news-card shadow">

        <!-- Gambar -->
        <div class="news-image-container">
            <img src="../assets/uploads/berita/<?= htmlspecialchars($data['gambar']) ?>">
        </div>

        <!-- Isi -->
        <div class="news-content">

            <h2 class="news-title"><?= htmlspecialchars($data['judul']) ?></h2>

            <p class="news-date">
                Dipublikasikan pada 
                <strong><?= date('d F Y', strtotime($data['tanggal'])) ?></strong>
            </p>

            <div class="news-body">
                <?= nl2br($data['isi']) ?>
            </div>

            <p class="news-author">
                Penulis: <strong><?= htmlspecialchars($data['penulis']) ?></strong>
            </p>

            <a href="berita.php" class="back-btn">← Kembali</a>

        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
