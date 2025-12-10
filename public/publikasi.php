<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// --- LOGIKA PENCARIAN (SEARCH) ---
$search_query = "";
$sql_search = "";

if (isset($_GET['q'])) {
    $search_query = pg_escape_string($conn, $_GET['q']);
    $sql_search = "WHERE judul ILIKE '%$search_query%' 
                   OR penulis ILIKE '%$search_query%' 
                   OR jurnal ILIKE '%$search_query%'";
}

// Ambil data publikasi
$query = "SELECT id, judul, penulis, jurnal, tahun, link_publikasi, file_publikasi 
          FROM public.publikasi
          $sql_search
          ORDER BY tahun DESC";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$dataPublikasi = pg_fetch_all($result);
?>

<style>
    /* 1. Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 80px 0;
        margin-bottom: 2rem;
        color: white;
    }

    /* 2. Styling Card Publikasi */
    .pub-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: none;
        border-radius: 15px;
        background-color: #fff;
        transition: all 0.3s ease;
        border-top: 5px solid #0d6efd; 
    }

    .pub-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .pub-card .card-body {
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
        min-height: 3rem;
        margin-bottom: 1rem;
        font-weight: 700;
        color: #2c3e50;
    }

    /* INFO ROW (Sama seperti Agenda - Icon Hitam) */
    .info-row {
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 5px;
        display: flex;
        align-items: start;
    }
    .info-row i {
        margin-right: 8px;
        color: #000; /* ICON HITAM */
        margin-top: 3px;
    }

    /* Wrapper Bagian Bawah (Tombol) */
    .card-bottom-section {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px dashed #eee;
        display: grid;
        gap: 10px; 
    }

    /* Search Box */
    .search-box {
        max-width: 500px;
        margin: 0 auto 50px auto;
        position: relative;
    }
    .search-box input {
        border-radius: 50px;
        padding: 12px 25px;
        border: 1px solid #ddd;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .search-box button {
        position: absolute;
        right: 5px;
        top: 5px;
        border-radius: 50px;
        padding: 7px 20px;
    }
</style>

<section class="hero-section text-center">
    <div class="container">
        <h1 class="fw-bold display-5">Publikasi Ilmiah</h1>
        <p class="lead opacity-75 mt-2">Kumpulan jurnal dan karya tulis Laboratorium Applied Informatics.</p>
    </div>
</section>

<section class="py-2">
    <div class="container">
        
        <h2 class="mb-4 text-center fw-bold" style="color: #333;">
            Daftar <span class="text-primary">Publikasi</span>
        </h2>

        <div class="search-box">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="q" class="form-control" placeholder="Cari judul, penulis, atau jurnal..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php if ($dataPublikasi): ?>
                <?php foreach ($dataPublikasi as $p): ?>
                    <div class="col">
                        <div class="card pub-card shadow-sm">
                            <div class="card-body">
                                
                                <h5 class="card-title mt-2">
                                    <?= htmlspecialchars($p['judul']) ?>
                                </h5>

                                <div class="info-row">
                                    <i class="bi bi-bookmarks"></i> 
                                    <div class="fst-italic"><?= htmlspecialchars($p['jurnal']) ?></div>
                                </div>

                                <div class="info-row">
                                    <i class="bi bi-pen"></i> 
                                    <div><?= htmlspecialchars($p['penulis']) ?></div>
                                </div>

                                <div class="info-row mb-3">
                                    <i class="bi bi-calendar-event"></i> 
                                    <div>Tahun: <?= htmlspecialchars($p['tahun']) ?></div>
                                </div>

                                <div class="card-bottom-section">
                                    
                                    <?php if (!empty($p['link_publikasi'])): ?>
                                        <a href="<?= htmlspecialchars($p['link_publikasi']) ?>" 
                                           target="_blank" 
                                           class="btn btn-outline-primary btn-sm w-100">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Kunjungi Web Jurnal
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!empty($p['file_publikasi'])): ?>
                                        <a href="../assets/uploads/publikasi/<?= $p['file_publikasi'] ?>" 
                                           target="_blank"
                                           class="btn btn-primary btn-sm w-100">
                                            <i class="bi bi-file-earmark-arrow-down me-1"></i> Unduh PDF
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-light btn-sm w-100 text-muted" disabled>
                                            <i class="bi bi-x-circle me-1"></i> PDF Tidak Tersedia
                                        </button>
                                    <?php endif; ?>

                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="100" class="mb-3 opacity-50">
                    <h4 class="text-muted">Publikasi tidak ditemukan.</h4>
                    <?php if(isset($_GET['q'])): ?>
                        <a href="publikasi.php" class="btn btn-link">Reset Pencarian</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>