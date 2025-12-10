<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// --- LOGIKA PENCARIAN (SEARCH) ---
$search_query = "";
$sql_search = "";

if (isset($_GET['q'])) {
    $search_query = pg_escape_string($conn, $_GET['q']);
    $sql_search = "WHERE judul ILIKE '%$search_query%' OR peneliti ILIKE '%$search_query%'";
}

// Ambil data penelitian
$query = "SELECT id, judul, peneliti, tahun, deskripsi 
          FROM public.penelitian 
          $sql_search
          ORDER BY tahun DESC";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$dataPenelitian = pg_fetch_all($result);
?>

<style>
    /* 1. Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 80px 0;
        margin-bottom: 2rem;
        color: white;
    }

    /* 2. Styling Card Penelitian */
    .research-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: none;
        border-radius: 15px;
        background-color: #fff;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border-left: 5px solid #0d6efd; 
    }

    .research-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .research-card .card-body {
        display: flex;
        flex-direction: column;
        flex: 1; 
        padding: 1.5rem;
    }

    /* Styling Judul */
    .card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
        color: #2c3e50;
    }

    /* Styling Deskripsi */
    .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1.5rem;
        color: #666;
        font-size: 0.95rem;
    }

    /* INFO ROW (Icon Hitam) */
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

    /* Wrapper untuk Meta Data (Peneliti & Tahun) agar selalu di bawah */
    .meta-wrapper {
        margin-top: auto; /* KUNCI: Mendorong ke bawah */
        margin-bottom: 10px;
    }

    /* Wrapper Tombol Bawah */
    .card-bottom-section {
        padding-top: 15px;
        border-top: 1px dashed #eee;
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
        <h1 class="fw-bold display-5">Hasil Penelitian</h1>
        <p class="lead opacity-75 mt-2">Arsip publikasi dan riset Laboratorium Applied Informatics.</p>
    </div>
</section>

<section class="py-2">
    <div class="container">
        
        <h2 class="mb-4 text-center fw-bold" style="color: #333;">
            Daftar <span class="text-primary">Penelitian</span>
        </h2>

        <div class="search-box">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="q" class="form-control" placeholder="Cari judul atau peneliti..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php if ($dataPenelitian): ?>
                <?php foreach ($dataPenelitian as $r): ?>
                    <div class="col">
                        <div class="card research-card shadow-sm">
                            <div class="card-body">
                                
                                <h5 class="card-title">
                                    <?= htmlspecialchars($r['judul']) ?>
                                </h5>

                                <p class="card-text">
                                    <?= mb_strimwidth(strip_tags($r['deskripsi']), 0, 120, "..."); ?>
                                </p>

                                <div class="meta-wrapper">
                                    <div class="info-row">
                                        <i class="bi bi-person-circle"></i> 
                                        <div><?= htmlspecialchars($r['peneliti']) ?></div>
                                    </div>

                                    <div class="info-row">
                                        <i class="bi bi-calendar-check"></i> 
                                        <div>Tahun: <?= htmlspecialchars($r['tahun']) ?></div>
                                    </div>
                                </div>

                                <div class="card-bottom-section">
                                    <a href="detail_penelitian.php?id=<?= $r['id'] ?>" 
                                       class="btn btn-outline-primary btn-sm w-100 fw-bold"
                                       style="border-radius: 20px;">
                                        Baca Detail Riset →
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="100" class="mb-3 opacity-50">
                    <h4 class="text-muted">Data penelitian tidak ditemukan.</h4>
                    <?php if(isset($_GET['q'])): ?>
                        <a href="penelitian.php" class="btn btn-link">Reset Pencarian</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>