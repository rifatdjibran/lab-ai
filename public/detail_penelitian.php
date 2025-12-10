<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    die("ID penelitian tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data penelitian berdasarkan ID
$query = "SELECT id, judul, peneliti, tahun, deskripsi 
          FROM public.penelitian 
          WHERE id = $id";

$result = pg_query($conn, $query);
$data = pg_fetch_assoc($result);

if (!$data) {
    die("Data penelitian tidak ditemukan.");
}
?>

<style>
    /* Wrapper Utama */
    .research-wrapper {
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

    /* Header Dekoratif pengganti gambar */
    .detail-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 60px 50px;
        color: white;
        text-align: center;
        position: relative;
    }

    /* Icon Background samar */
    .detail-header i.bg-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 10rem;
        opacity: 0.1;
        pointer-events: none;
    }

    /* Konten Body */
    .detail-content {
        padding: 40px 50px;
    }

    /* Judul Besar */
    .detail-title {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 10px;
        line-height: 1.3;
        position: relative;
        z-index: 2;
    }

    /* Kotak Informasi Peneliti & Tahun */
    .info-box {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 20px;
        margin-top: -40px; /* Overlap ke header atas */
        margin-bottom: 30px;
        position: relative;
        z-index: 3;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        max-width: 80%;
        margin-left: auto;
        margin-right: auto;
    }

    .info-item {
        text-align: center;
        padding: 10px;
    }
    .info-label {
        font-size: 0.85rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .info-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
    }

    /* Isi Deskripsi - PERBAIKAN POTONGAN TEKS DISINI */
    .detail-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #444;
        text-align: justify;
        
        /* CSS ini memaksa kata super panjang (aaaaa...) untuk patah ke baris baru */
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
    }

    /* Tombol Kembali */
    .back-btn {
        margin-top: 40px;
        display: inline-flex;
        align-items: center;
        padding: 12px 25px;
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
        .detail-header { padding: 40px 20px; }
        .detail-title { font-size: 1.5rem; }
        .detail-content { padding: 25px; }
        .info-box { flex-direction: column; gap: 15px; width: 90%; }
    }
</style>

<section class="hero-section text-center">
    <div class="container">
        <h1 class="fw-bold display-5">Hasil Penelitian</h1>
        <p class="lead opacity-75 mt-2">Arsip publikasi dan riset Laboratorium Applied Informatics.</p>
    </div>
</section>

<div class="research-wrapper">
    <div class="container">
        
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">Detail <span class="text-primary">Penelitian</span></h2>
        </div>

        <div class="detail-card shadow-lg">

            <div class="detail-header">
                <i class="bi bi-journal-text bg-icon"></i> <h1 class="detail-title"><?= htmlspecialchars($data['judul']) ?></h1>
            </div>

            <div class="info-box">
                <div class="info-item border-end pe-md-5">
                    <div class="info-label">Tahun Penelitian</div>
                    <div class="info-value">
                        <i class="bi bi-calendar-check text-primary me-1"></i> 
                        <?= htmlspecialchars($data['tahun']) ?>
                    </div>
                </div>
                <div class="info-item ps-md-5">
                    <div class="info-label">Peneliti Utama</div>
                    <div class="info-value">
                        <i class="bi bi-person-circle text-primary me-1"></i> 
                        <?= htmlspecialchars($data['peneliti']) ?>
                    </div>
                </div>
            </div>

            <div class="detail-content">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Deskripsi</h5>

                <div class="detail-body">
                    <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                </div>

                <div class="text-center">
                    <a href="penelitian.php" class="back-btn">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Penelitian
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>