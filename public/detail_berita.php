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
/* Wrapper utama dengan background tipis agar card menonjol */
.news-wrapper {
    background-color: #f8f9fa; /* Abu-abu sangat muda */
    padding-top: 50px;
    padding-bottom: 80px;
    min-height: 100vh; /* Agar footer tidak naik jika konten sedikit */
}

/* Judul Halaman di atas Card */
.page-title {
    font-weight: 800;
    font-size: 2.5rem;
    margin-bottom: 30px;
    color: #333;
}

/* Warna khusus untuk kata "Berita" */
.text-highlight {
    color: #0d6efd; /* Biru Bootstrap */
}

/* ------------------------------ */
/* CARD DETAIL BERITA */
/* ------------------------------ */
.news-card {
    max-width: 900px;
    margin: auto;
    border-radius: 20px;
    background: #fff;
    border: none; /* Hilangkan border biasa */
    overflow: hidden;
    padding-bottom: 25px;
}

/* Gambar */
.news-image-container {
    width: 100%;
    height: 400px; /* Tinggi fix untuk area gambar */
    background-color: #000; /* Background hitam biar elegan */
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

.news-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Gambar mengisi area (crop rapi) */
    opacity: 0.9;
}

/* Isi card container */
.news-content {
    padding: 40px 50px;
}

/* Judul Berita */
.news-title {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.3;
    color: #222;
    margin-bottom: 15px;
}

/* Metadata (Tanggal & Penulis) */
.news-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee; /* Garis pemisah */
}

.news-meta i {
    margin-right: 5px;
}

/* Isi deskripsi */
.news-body {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
    text-align: justify; /* Teks rata kanan-kiri agar rapi */
}

/* Tombol back */
.back-btn {
    margin-top: 40px;
    display: inline-flex;
    align-items: center;
    padding: 12px 25px;
    border-radius: 50px; /* Tombol bulat */
    background: #0d6efd;
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
}

.back-btn:hover {
    background: #0b59d1;
    transform: translateY(-3px); /* Efek naik sedikit */
    box-shadow: 0 6px 12px rgba(13, 110, 253, 0.3);
    color: #fff;
}

/* Responsif */
@media(max-width: 768px) {
    .page-title { font-size: 2rem; }
    .news-card { max-width: 95%; }
    .news-content { padding: 25px; }
    .news-title { font-size: 1.5rem; }
    .news-image-container { height: 250px; }
}
</style>

<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Berita Laboratorium</h1>
        <p class="lead mt-2">Ikuti informasi dan update terbaru dari Laboratorium</p>
    </div>
</section>

<div class="news-wrapper">
    <div class="container">
        
        <div class="text-center">
            <h2 class="page-title">
                Detail <span class="text-highlight">Berita</span>
            </h2>
        </div>

        <div class="news-card shadow-lg"> <div class="news-image-container">
                <img 
                    src="../assets/uploads/berita/<?= htmlspecialchars($data['gambar']) ?>" 
                    alt="<?= htmlspecialchars($data['judul']) ?>">
            </div>

            <div class="news-content">

                <span class="badge bg-light text-primary mb-2">Informasi Terbaru</span>

                <h1 class="news-title"><?= htmlspecialchars($data['judul']) ?></h1>

                <div class="news-meta">
                    <div>
                        <i class="bi bi-calendar-event"></i> 
                        <?= date('d F Y', strtotime($data['tanggal'])) ?>
                    </div>
                    <div>
                        <i class="bi bi-person-circle"></i> 
                        <?= htmlspecialchars($data['penulis']) ?>
                    </div>
                </div>

                <div class="news-body">
                    <?= nl2br($data['isi']) ?>
                </div>

                <div class="text-center"> <a href="berita.php" class="back-btn">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Berita
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>