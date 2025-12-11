<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

require_once "../config/database.php";

// --- 1. DATA STATISTIK ---
// Array tabel dan label untuk looping query count
$tables = [
    'berita' => ['label' => 'Berita', 'icon' => 'bi-newspaper', 'color' => 'primary'],
    'kegiatan' => ['label' => 'Agenda', 'icon' => 'bi-calendar-event', 'color' => 'success'],
    'penelitian' => ['label' => 'Penelitian', 'icon' => 'bi-journal-text', 'color' => 'warning'],
    'publikasi' => ['label' => 'Publikasi', 'icon' => 'bi-journal-medical', 'color' => 'info'],
    'fasilitas' => ['label' => 'Fasilitas', 'icon' => 'bi-building', 'color' => 'danger'],
    'kontak' => ['label' => 'Pesan Masuk', 'icon' => 'bi-envelope', 'color' => 'secondary']
];

$stats = [];
foreach ($tables as $table => $info) {
    $query = pg_query($conn, "SELECT COUNT(*) FROM $table");
    $stats[$table] = pg_fetch_result($query, 0, 0);
}

// --- 2. DATA KETUA LAB ---
// Mencari jabatan yang mengandung kata 'Ketua' atau 'Kepala'
$qKetua = pg_query($conn, "SELECT * FROM struktur_organisasi WHERE jabatan ILIKE '%Ketua%' OR jabatan ILIKE '%Kepala%' LIMIT 1");
$ketua = pg_fetch_assoc($qKetua);

// --- 3. PESAN TERBARU (5 Terakhir) ---
$qPesan = pg_query($conn, "SELECT * FROM kontak ORDER BY tanggal DESC LIMIT 5");

// --- 4. AGENDA TERDEKAT (Mendatang) ---
$today = date('Y-m-d');
$qAgenda = pg_query($conn, "SELECT * FROM kegiatan WHERE tanggal_mulai >= '$today' ORDER BY tanggal_mulai ASC LIMIT 1");
$agenda = pg_fetch_assoc($qAgenda);

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Admin Lab AI</title>

  <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
  <link rel="stylesheet" href="../assets/css/admin.css">
  
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* =========================================================
       INTERNAL CSS - DASHBOARD SPECIFIC
       ========================================================= */
    :root {
        --primary-color: #4361ee;
        --bg-body: #f3f5f9;
        --text-main: #2b3674;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
        overflow-x: hidden;
    }

    /* STAT CARDS */
    .stat-card {
        border: none;
        border-radius: 16px;
        background: white;
        padding: 20px;
        transition: 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .icon-box {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }

    /* KETUA CARD */
    .ketua-card {
        background: linear-gradient(135deg, #4361ee, #4cc9f0);
        color: white;
        border: none;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }
    .ketua-card .ketua-img {
        width: 80px; height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.5);
    }

    /* TABLE SECTION */
    .content-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        padding: 25px;
        height: 100%;
    }

    .mini-table th { font-weight: 600; font-size: 0.85rem; color: #a3aed0; border:none; }
    .mini-table td { padding: 15px 10px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; vertical-align: middle; }
    .mini-table tr:last-child td { border-bottom: none; }

    /* AGENDA BADGE */
    .agenda-date {
        background: #f4f7fe;
        color: var(--primary-color);
        padding: 10px 15px;
        border-radius: 12px;
        text-align: center;
        font-weight: 700;
        min-width: 70px;
    }
  </style>
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="d-flex">
    <?php include "../includes/sidebar.php"; ?>

    <div id="main-content" class="p-4 p-md-5 flex-grow-1" style="min-height: 100vh;">

        <div class="mb-5">
            <h2 class="fw-bold mb-1" style="color: var(--text-main);">Dashboard Overview</h2>
            <p class="text-muted">Halo Admin, berikut ringkasan aktivitas Lab AI hari ini.</p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-5">
            <?php foreach ($tables as $key => $t): ?>
            <div class="col">
                <div class="stat-card">
                    <div>
                        <p class="text-muted small fw-bold mb-1 text-uppercase"><?= $t['label'] ?></p>
                        <h3 class="fw-bold mb-0 text-dark"><?= $stats[$key] ?></h3>
                    </div>
                    <div class="icon-box bg-<?= $t['color'] ?> bg-opacity-10 text-<?= $t['color'] ?>">
                        <i class="bi <?= $t['icon'] ?>"></i>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                
                <div class="card ketua-card mb-4 shadow-lg">
                    <div class="card-body p-4 d-flex align-items-center">
                        <?php if ($ketua): ?>
                            <div class="me-4">
                                <?php if (!empty($ketua['foto'])): ?>
                                    <img src="../assets/img/tim/<?= $ketua['foto'] ?>" class="ketua-img" alt="Ketua">
                                <?php else: ?>
                                    <div class="ketua-img bg-white text-primary d-flex align-items-center justify-content-center display-4">
                                        <i class="bi bi-person"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold mb-1 letter-spacing-1">Kepala Laboratorium</h6>
                                <h3 class="fw-bold mb-1"><?= $ketua['nama'] ?></h3>
                                <p class="mb-0 opacity-75 small"><i class="bi bi-envelope me-2"></i><?= $ketua['email'] ?></p>
                            </div>
                        <?php else: ?>
                            <div class="text-center w-100 py-3">
                                <i class="bi bi-exclamation-circle text-white-50 fs-1 mb-2"></i>
                                <h5 class="fw-bold">Belum ada Kepala Lab</h5>
                                <a href="tambah_tim.php" class="btn btn-sm btn-light text-primary fw-bold mt-2">Tambah Data</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Pesan & Masukan Terbaru</h5>
                        <a href="kontakAdmin.php" class="btn btn-sm btn-light text-primary fw-bold">Lihat Semua</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table mini-table table-borderless w-100">
                            <thead>
                                <tr>
                                    <th>PENGIRIM</th>
                                    <th>SUBJEK</th>
                                    <th>WAKTU</th>
                                    <th class="text-end">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (pg_num_rows($qPesan) > 0): ?>
                                    <?php while($msg = pg_fetch_assoc($qPesan)): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 fw-bold" style="width:35px; height:35px; font-size:0.8rem;">
                                                    <?= strtoupper(substr($msg['nama'], 0, 1)) ?>
                                                </div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($msg['nama']) ?></div>
                                            </div>
                                        </td>
                                        <td class="text-muted"><?= substr(htmlspecialchars($msg['subjek']), 0, 30) ?>...</td>
                                        <td class="text-muted small"><?= date('d M', strtotime($msg['tanggal'])) ?></td>
                                        <td class="text-end">
                                            <?php if($msg['status'] == 'baru'): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Baru</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Dibaca</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada pesan masuk.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="content-card bg-primary text-white" style="background: linear-gradient(180deg, #3a0ca3 0%, #4361ee 100%);">
                    <h5 class="fw-bold mb-4"><i class="bi bi-calendar-check me-2"></i>Agenda Terdekat</h5>
                    
                    <?php if ($agenda): ?>
                        <div class="bg-white bg-opacity-10 p-4 rounded-4 mb-3 border border-white border-opacity-10">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white text-primary rounded-3 px-3 py-2 text-center me-3 fw-bold lh-sm">
                                    <span class="d-block small text-uppercase"><?= date('M', strtotime($agenda['tanggal_mulai'])) ?></span>
                                    <span class="fs-4"><?= date('d', strtotime($agenda['tanggal_mulai'])) ?></span>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($agenda['nama_kegiatan']) ?></h6>
                                    <small class="text-white-50"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($agenda['lokasi']) ?></small>
                                </div>
                            </div>
                            <p class="small text-white-50 mb-3" style="line-height: 1.6;">
                                Segera persiapkan kebutuhan untuk kegiatan ini. Cek detail lengkap di menu kegiatan.
                            </p>
                            <a href="agendaAdmin.php" class="btn btn-light w-100 text-primary fw-bold rounded-pill">Lihat Detail</a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x fs-1 text-white-50 mb-3"></i>
                            <p>Tidak ada agenda mendatang.</p>
                            <a href="tambah_agenda.php" class="btn btn-outline-light rounded-pill btn-sm">Buat Agenda</a>
                        </div>
                    <?php endif; ?>

                    <hr class="border-white border-opacity-25 my-4">

                    <h6 class="fw-bold mb-3 small text-uppercase text-white-50">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="tambah_berita.php" class="btn btn-outline-light text-start border-0 bg-white bg-opacity-10 hover-bg-opacity-20">
                            <i class="bi bi-pencil-square me-2"></i> Tulis Berita Baru
                        </a>
                        <a href="tambah_publikasi.php" class="btn btn-outline-light text-start border-0 bg-white bg-opacity-10 hover-bg-opacity-20">
                            <i class="bi bi-upload me-2"></i> Upload Publikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>