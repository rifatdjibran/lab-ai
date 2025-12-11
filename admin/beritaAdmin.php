<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}
require_once "../config/database.php";

// LOGIKA HAPUS BERITA
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Ambil nama file gambar dulu untuk dihapus dari folder
    $g = pg_query($conn, "SELECT gambar FROM berita WHERE id=$id");
    $gm = pg_fetch_assoc($g)['gambar'];

    // Hapus file fisik jika ada
    if (!empty($gm) && file_exists("../assets/uploads/berita/" . $gm)) {
        unlink("../assets/uploads/berita/" . $gm);
    }

    // Hapus data dari database
    pg_query($conn, "DELETE FROM berita WHERE id=$id");

    header("Location: beritaAdmin.php?hapus=1");
    exit;
}

// AMBIL DATA BERITA
$result = pg_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Berita | Admin Lab AI</title>

  <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
  <link rel="stylesheet" href="../assets/css/admin.css">
  
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* =========================================================
       INTERNAL CSS - TEMA MODERN SAAS (BIRU & CLEAN)
       ========================================================= */
    
    :root {
        --primary-color: #4361ee;       /* Biru Utama */
        --primary-dark: #3a0ca3;        /* Biru Gelap (Hover) */
        --accent-color: #4cc9f0;        /* Aksen Biru Muda */
        --bg-body: #f3f5f9;             /* Background Halaman (Abu Sejuk) */
        --text-main: #2b3674;           /* Warna Teks Utama */
        --text-muted: #a3aed0;          /* Warna Teks Pudar */
        --white: #ffffff;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
        overflow-x: hidden;
    }

    a { text-decoration: none; }
    ul { list-style: none; padding: 0; margin: 0; }

    /* SAYA MENGHAPUS OVERRIDE SIDEBAR DISINI AGAR MENGIKUTI SIDEBAR.PHP */

    /* --- PAGE HEADER --- */
    .page-title {
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    /* --- TOMBOL CUSTOM --- */
    .btn-primary-custom {
        background-color: var(--primary-color);
        color: white;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        border: none;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary-custom:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        color: white;
    }

    /* --- TABEL MODERN (FLOATING ROWS) --- */
    .table-container { 
        padding-bottom: 50px; 
    }

    .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px; /* Jarak antara Header dan Baris Data */
        }

        /* HEADER TABLE (Dibuat Menonjol & Bold) */
        .custom-table thead tr {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }

        .custom-table thead th {
            font-size: 0.8rem;
            color: var(--text-muted); 
            font-weight: 800; /* BOLD */
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            padding: 18px 20px;
        }

        /* Rounded Corners untuk Header */
        .custom-table thead th:first-child {
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }
        .custom-table thead th:last-child {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

    /* Body Rows (Card Style) */
    .custom-table tbody tr {
        background: var(--white);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02); /* Bayangan halus */
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-table tbody td {
        padding: 20px;
        vertical-align: middle;
        border: none;
        color: var(--text-main);
        font-size: 0.95rem;
    }

    /* Rounded Corners per Baris */
    .custom-table tbody td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
    .custom-table tbody td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }

    /* Hover Effect Baris */
    .custom-table tbody tr:hover {
        transform: translateY(-4px); /* Naik ke atas */
        box-shadow: 0 15px 30px rgba(67, 97, 238, 0.1); /* Bayangan biru */
        z-index: 10;
        position: relative;
    }

    /* --- KOMPONEN LAIN --- */
    /* Gambar di Tabel */
    .table-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border: 2px solid #fff;
    }
    
    .table-img-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background-color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
        font-size: 1.5rem;
    }

    /* Tombol Aksi (Titik Tiga) */
    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: var(--text-muted);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .btn-action:hover, .dropdown.show .btn-action {
        background: #f4f7fe;
        color: var(--primary-color);
    }

    /* Dropdown */
    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        border-radius: 12px;
        padding: 8px;
    }
    .dropdown-item {
        border-radius: 8px;
        font-size: 0.9rem;
        padding: 8px 12px;
        color: var(--text-main);
        font-weight: 500;
    }
    .dropdown-item:hover {
        background-color: #f4f7fe;
        color: var(--primary-color);
    }

    /* Alert */
    .alert-custom {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        color: var(--text-main);
    }
  </style>
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="d-flex">

  <?php include "../includes/sidebar.php"; ?>

  <div id="main-content" class="p-4 p-md-5 flex-grow-1" style="min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="page-title display-6 mb-1">Manajemen Berita</h2>
            <p class="text-muted mb-0">Kelola artikel dan informasi terbaru Lab AI.</p>
        </div>
        
        <div>
            <a href="tambah_berita.php" class="btn btn-primary-custom">
                <i class="bi bi-plus-lg"></i> 
                <span>Tambah Berita</span>
            </a>
        </div>
    </div>

    <?php if (isset($_GET['hapus'])) { ?>
        <div class="alert alert-custom alert-dismissible fade show p-3 mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
            <div class="fw-medium">Data berita berhasil dihapus.</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th class="ps-4">No</th>
                    <th>Gambar</th>
                    <th>Judul Berita</th>
                    <th>Penulis</th>
                    <th>Tanggal</th>
                    <th class="text-center pe-4">Menu</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (pg_num_rows($result) > 0) {
                    $no = 1; 
                    while ($b = pg_fetch_assoc($result)) { 
                ?>
                <tr>
                    <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                    
                    <td>
                        <?php if (!empty($b['gambar'])): ?>
                            <img src="../assets/uploads/berita/<?= htmlspecialchars($b['gambar']); ?>" class="table-img" alt="Thumb">
                        <?php else: ?>
                            <div class="table-img-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td>
                        <span class="fw-bold text-dark text-wrap" style="display: block; max-width: 300px; line-height: 1.4;">
                            <?= htmlspecialchars($b['judul']); ?>
                        </span>
                    </td>

                    <td>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-person-circle me-2 text-primary opacity-50"></i>
                            <span class="small fw-bold"><?= htmlspecialchars($b['penulis']); ?></span>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-secondary" style="font-size: 0.85rem;">
                                <?= date('d M Y', strtotime($b['tanggal'])); ?>
                            </span>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <?= date('H:i', strtotime($b['tanggal'])); ?> WIB
                            </small>
                        </div>
                    </td>

                    <td class="text-center pe-4">
                        <div class="dropdown">
                            <button class="btn-action" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="edit_berita.php?id=<?= $b['id']; ?>">
                                        <i class="bi bi-pencil-square me-2 text-primary opacity-75"></i> Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item text-danger d-flex align-items-center" 
                                       onclick="return confirm('Hapus berita ini permanen?')"
                                       href="beritaAdmin.php?delete=<?= $b['id']; ?>">
                                        <i class="bi bi-trash me-2 opacity-75"></i> Hapus
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <div class="bg-light rounded-circle p-4 mb-3">
                                <i class="bi bi-newspaper text-muted display-4"></i>
                            </div>
                            <h6 class="text-muted fw-bold">Belum ada berita</h6>
                            <p class="text-muted small">Tambahkan berita terbaru untuk ditampilkan di portal.</p>
                            <a href="tambah_berita.php" class="btn btn-sm btn-primary-custom mt-2">Buat Berita Baru</a>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>