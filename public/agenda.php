<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// --- LOGIKA PENCARIAN (SEARCH) ---
$search_query = "";
$sql_search = "";

if (isset($_GET['q'])) {
    $search_query = pg_escape_string($conn, $_GET['q']);
    // Cari berdasarkan nama kegiatan, lokasi, atau deskripsi
    $sql_search = "WHERE nama_kegiatan ILIKE '%$search_query%' 
                   OR lokasi ILIKE '%$search_query%' 
                   OR deskripsi ILIKE '%$search_query%'";
}

// Query data kegiatan
$query = "SELECT id, nama_kegiatan, deskripsi, tanggal_mulai, tanggal_selesai,
                 lokasi, gambar
          FROM public.kegiatan
          $sql_search
          ORDER BY tanggal_mulai ASC";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$agendaLab = pg_fetch_all($result);
?>

<style>
    /* 1. Hero Section (Konsisten) */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 80px 0;
        margin-bottom: 2rem;
        color: white;
    }

    /* 2. Styling Card Agenda */
    .agenda-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: none;
        border-radius: 15px;
        background-color: #fff;
        transition: all 0.3s ease;
        overflow: hidden; /* Agar gambar tidak keluar dari radius */
    }

    .agenda-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* Gambar Card */
    .agenda-card .card-img-top {
        height: 220px;
        object-fit: cover;
        border-bottom: 1px solid #f0f0f0;
    }

    /* Body Card */
    .agenda-card .card-body {
        display: flex;
        flex-direction: column;
        flex: 1; 
        padding: 1.5rem;
    }

    /* Judul */
    .card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Max 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3rem; /* Menjaga tinggi area judul */
        margin-bottom: 0.8rem;
        font-weight: 700;
        color: #2c3e50;
    }

    /* Deskripsi */
    .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 3; /* Max 3 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1rem;
        font-size: 0.95rem;
        color: #666;
    }

    /* Info Lokasi & Tanggal */
    .info-row {
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 5px;
        display: flex;
        align-items: start;
    }
    
    /* --- PERUBAHAN WARNA ICON DI SINI --- */
    .info-row i {
        margin-right: 8px;
        color: #000; /* Berubah jadi Hitam */
        margin-top: 3px;
    }

    /* Wrapper Bagian Bawah (Status) */
    .card-bottom-section {
        margin-top: auto; /* KUNCI: Dorong ke bawah */
        padding-top: 15px;
        border-top: 1px dashed #eee;
    }

    /* Status Badge yang lebih besar */
    .badge-status {
        width: 100%;
        padding: 10px;
        font-size: 0.9rem;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
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
        <h1 class="fw-bold display-5">Agenda Laboratorium</h1>
        <p class="lead opacity-75 mt-2">Jadwal kegiatan, workshop, dan acara mendatang.</p>
    </div>
</section>

<section class="py-2">
    <div class="container">
        
        <h2 class="mb-4 text-center fw-bold" style="color: #333;">
            Jadwal <span class="text-primary">Kegiatan</span>
        </h2>

        <div class="search-box">
            <form action="" method="GET" class="d-flex">
                <input type="text" name="q" class="form-control" placeholder="Cari agenda atau lokasi..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php if ($agendaLab): ?>
                <?php foreach ($agendaLab as $kegiatan): ?>
                    <div class="col">
                        <div class="card agenda-card shadow-sm">

                            <img src="../assets/uploads/kegiatan/<?= htmlspecialchars($kegiatan['gambar']) ?>"
                                 class="card-img-top"
                                 alt="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>">

                            <div class="card-body">
                                
                                <h5 class="card-title">
                                    <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                                </h5>

                                <p class="card-text">
                                    <?= mb_strimwidth(strip_tags($kegiatan['deskripsi']), 0, 110, "..."); ?>
                                </p>

                                <div class="info-row">
                                    <i class="bi bi-calendar-event"></i>
                                    <div>
                                        <?= date('d M Y', strtotime($kegiatan['tanggal_mulai'])) ?> 
                                        <?php if($kegiatan['tanggal_mulai'] != $kegiatan['tanggal_selesai']): ?>
                                            - <?= date('d M Y', strtotime($kegiatan['tanggal_selesai'])) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="info-row mb-3">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <div><?= htmlspecialchars($kegiatan['lokasi']) ?></div>
                                </div>

                                <div class="card-bottom-section">
                                    <?php
                                        $today = date('Y-m-d');
                                        $mulai = $kegiatan['tanggal_mulai'];
                                        $selesai = $kegiatan['tanggal_selesai'];
                                        
                                        // Logika Status
                                        if ($today < $mulai) {
                                            echo '<div class="badge bg-warning text-dark badge-status"><i class="bi bi-hourglass-split me-2"></i>Segera</div>';
                                        } elseif ($today >= $mulai && $today <= $selesai) {
                                            echo '<div class="badge bg-success badge-status"><i class="bi bi-play-circle me-2"></i>Sedang Berlangsung</div>';
                                        } else {
                                            echo '<div class="badge bg-secondary badge-status"><i class="bi bi-check-circle me-2"></i>Selesai</div>';
                                        }
                                    ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="100" class="mb-3 opacity-50">
                    <h4 class="text-muted">Agenda tidak ditemukan.</h4>
                    <?php if(isset($_GET['q'])): ?>
                        <a href="agenda.php" class="btn btn-link">Reset Pencarian</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>