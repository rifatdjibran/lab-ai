<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// --- LOGIKA PENCARIAN (SEARCH) ---
$search_query = "";
$sql_search = "";

if (isset($_GET['q'])) {
    $search_query = pg_escape_string($conn, $_GET['q']);
    // Menggunakan ILIKE untuk pencarian tidak sensitif huruf besar/kecil (PostgreSQL)
    $sql_search = "WHERE judul ILIKE '%$search_query%' OR penulis ILIKE '%$search_query%'";
}

// Ambil berita terbaru dengan filter pencarian (jika ada)
$query = "SELECT id, judul, isi, gambar, tanggal, penulis 
          FROM berita 
          $sql_search
          ORDER BY tanggal DESC";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$beritaList = pg_fetch_all($result);
?>

<style>
    /* 1. Hero Section dengan Gradient */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 80px 0;
        margin-bottom: 2rem;
        color: white;
    }

    /* 2. Styling Card agar Fix Height & Rapi */
    .agenda-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: none; /* Menghilangkan garis pinggir */
        border-radius: 15px; /* Sudut lebih membulat */
        transition: all 0.3s ease;
        background-color: #fff;
    }

    /* Efek Hover: Card naik sedikit & bayangan menebal */
    .agenda-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .agenda-card .card-img-top {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .agenda-card .card-body {
        display: flex;
        flex-direction: column;
        flex: 1; 
        padding: 1.5rem;
    }

    /* Styling Teks */
    .card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3rem;
        margin-bottom: 0.8rem;
        font-weight: 700;
        color: #333;
    }

    .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1rem;
        font-size: 0.95rem;
        color: #666;
    }

    /* Wrapper bawah (Info + Tombol) */
    .card-bottom-section {
        margin-top: auto; 
    }

    /* Form Pencarian */
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
        <h1 class="fw-bold display-5">Berita Laboratorium</h1>
        <p class="lead opacity-75 mt-2">Update informasi terkini seputar kegiatan dan teknologi.</p>
    </div>
</section>

<section class="py-2">
    <div class="container">
        
        <h2 class="mb-4 text-center fw-bold" style="color: #333;">
            Berita <span class="text-primary">Terbaru</span>
        </h2>

        <div class="search-box">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="q" class="form-control" placeholder="Cari judul berita..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php if ($beritaList): ?>
                <?php foreach ($beritaList as $b): ?>
                    <div class="col"> 
                        <div class="card agenda-card shadow-sm">

                            <img 
                                src="../assets/uploads/berita/<?= htmlspecialchars($b['gambar']) ?>" 
                                class="card-img-top"
                                style="height: 220px; object-fit: cover;"
                                alt="<?= htmlspecialchars($b['judul']) ?>">

                            <div class="card-body">

                                <h5 class="card-title">
                                    <?= htmlspecialchars($b['judul']) ?>
                                </h5>

                                <p class="card-text">
                                    <?= mb_strimwidth(strip_tags($b['isi']), 0, 110, "..."); ?>
                                </p>

                                <div class="card-bottom-section">
                                    
                                    <div class="mb-3 pt-3 border-top small text-muted"> 
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>
                                                <i class="bi bi-calendar3 me-1"></i> 
                                                <?= date('d M Y', strtotime($b['tanggal'])) ?>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>
                                                <i class="bi bi-person-circle me-1"></i> 
                                                <?= htmlspecialchars($b['penulis']) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <a href="detail_berita.php?id=<?= $b['id'] ?>" 
                                           class="btn btn-outline-primary btn-sm w-100 fw-bold" 
                                           style="border-radius: 20px;">
                                           Baca Selengkapnya →
                                        </a>
                                    </div>

                                </div> 
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="100" class="mb-3 opacity-50">
                    <h4 class="text-muted">Tidak ada berita ditemukan.</h4>
                    <?php if(isset($_GET['q'])): ?>
                        <a href="berita.php" class="btn btn-link">Reset Pencarian</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>