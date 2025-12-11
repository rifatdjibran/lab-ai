<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

require_once "../config/database.php";

// LOGIKA HAPUS PUBLIKASI
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Ambil info file
    $q = pg_query($conn, "SELECT file_publikasi FROM publikasi WHERE id = $id");
    $file = pg_fetch_assoc($q)['file_publikasi'];

    // Hapus file fisik jika ada
    if (!empty($file) && file_exists("../assets/uploads/publikasi/" . $file)) {
        unlink("../assets/uploads/publikasi/" . $file);
    }

    // Hapus data dari database
    pg_query($conn, "DELETE FROM publikasi WHERE id = $id");

    header("Location: publikasiAdmin.php?hapus=1");
    exit;
}

// AMBIL DATA PUBLIKASI (JOIN dengan struktur_organisasi)
$sql_data = "SELECT p.*, so.nama AS nama_anggota_utama 
             FROM publikasi p
             LEFT JOIN struktur_organisasi so ON p.id_anggota_utama = so.id 
             ORDER BY p.tahun DESC";
$result = pg_query($conn, $sql_data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Publikasi | Admin Lab AI</title>

  <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
  <link rel="stylesheet" href="../assets/css/admin.css">
  
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* =========================================================
       INTERNAL CSS
       ========================================================= */
    
    :root {
        --primary-color: #4361ee;
        --primary-dark: #3a0ca3;
        --accent-color: #4cc9f0;
        --bg-body: #f3f5f9;
        --text-main: #2b3674;
        --text-muted: #a3aed0;
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

    /* --- PAGE HEADER --- */
    .page-title { font-weight: 700; color: var(--text-main); letter-spacing: -0.5px; }

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
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-primary-custom:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        color: white;
    }

    /* --- TABEL MODERN --- */
    .table-container { padding-bottom: 50px; }

    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; }

    .custom-table thead tr { background-color: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }

    .custom-table thead th {
        font-size: 0.8rem; color: var(--text-muted); font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.8px;
        border: none; padding: 18px 20px; white-space: nowrap;
    }

    .custom-table thead th:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
    .custom-table thead th:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }

    .custom-table tbody tr {
        background: var(--white); box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-table tbody td {
        padding: 20px; vertical-align: middle; border: none;
        color: var(--text-main); font-size: 0.95rem;
    }

    .custom-table tbody td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
    .custom-table tbody td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }

    .custom-table tbody tr:hover {
        transform: translateY(-4px); box-shadow: 0 15px 30px rgba(67, 97, 238, 0.1);
        z-index: 10; position: relative;
    }

    /* --- HELPERS --- */
    .text-truncate-2 {
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; text-overflow: ellipsis;
    }

    .badge-year {
        background-color: #f0f3ff; color: var(--primary-color);
        padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem;
    }

    .badge-category {
        background-color: #f8f9fa; color: #666; border: 1px solid #eee;
        padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
    }

    .btn-icon-soft {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s; border: 1px solid transparent;
    }
    .btn-icon-soft.pdf { background: #fee2e2; color: #ef4444; }
    .btn-icon-soft.pdf:hover { background: #ef4444; color: white; }
    
    .btn-icon-soft.link { background: #e0f2fe; color: #0ea5e9; }
    .btn-icon-soft.link:hover { background: #0ea5e9; color: white; }

    .btn-action {
        width: 35px; height: 35px; border-radius: 50%; border: none;
        background: transparent; color: var(--text-muted);
        transition: all 0.2s; display: flex; align-items: center; justify-content: center; margin: 0 auto;
    }
    .btn-action:hover { background: #f4f7fe; color: var(--primary-color); }

    .dropdown-menu { border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border-radius: 12px; padding: 8px; }
    .dropdown-item { border-radius: 8px; font-size: 0.9rem; padding: 8px 12px; color: var(--text-main); font-weight: 500; }
    .dropdown-item:hover { background-color: #f4f7fe; color: var(--primary-color); }

    /* Alert */
    .alert-custom { background: white; border: none; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); color: var(--text-main); }
  </style>
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="d-flex">
    <?php include "../includes/sidebar.php"; ?>

    <div id="main-content" class="p-4 p-md-5 flex-grow-1" style="min-height: 100vh;">

        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="page-title display-6 mb-1">Manajemen Publikasi</h2>
                <p class="text-muted mb-0">Kumpulan jurnal, paper, dan artikel ilmiah Lab AI.</p>
            </div>
            
            <div>
                <a href="tambah_publikasi.php" class="btn btn-primary-custom">
                    <i class="bi bi-plus-lg"></i> 
                    <span>Tambah Publikasi</span>
                </a>
            </div>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-custom alert-dismissible fade show p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                <div class="fw-medium">Data publikasi berhasil dihapus.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th width="35%">Judul Publikasi</th>
                        <th width="20%">Penulis</th>
                        <th>Jurnal</th>
                        <th>Tahun</th>
                        <th>Akses</th>
                        <th class="text-center pe-4">Menu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (pg_num_rows($result) > 0) {
                        $no = 1; 
                        while ($p = pg_fetch_assoc($result)) { 
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                        
                        <td>
                            <div class="fw-bold text-dark text-truncate-2" style="line-height: 1.5;" title="<?= htmlspecialchars($p['judul']); ?>">
                                <?= htmlspecialchars($p['judul']); ?>
                            </div>
                            <span class="badge-category mt-2 d-inline-block"><?= htmlspecialchars($p['kategori']); ?></span>
                        </td>

                        <td>
                            <div class="d-flex flex-column">
                                <?php if ($p['id_anggota_utama']): ?>
                                    <div class="d-flex align-items-start mb-1 text-primary fw-bold" style="font-size: 0.9rem;">
                                        <i class="bi bi-person-check-fill me-2 mt-1"></i> 
                                        <div><?= htmlspecialchars($p['nama_anggota_utama']); ?></div>
                                    </div>
                                    <small class="text-muted ms-4 ps-1" style="font-size: 0.75rem;">Tim Lab AI</small>
                                <?php else: ?>
                                    <span class="text-dark fw-medium"><?= htmlspecialchars($p['penulis']); ?></span>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Eksternal</small>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td>
                            <div class="text-muted fw-medium" style="max-width: 200px; word-break: break-word; line-height: 1.4;">
                                <?= htmlspecialchars($p['jurnal']); ?>
                            </div>
                        </td>

                        <td>
                            <span class="badge-year"><?= htmlspecialchars($p['tahun']); ?></span>
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <?php if (!empty($p['file_publikasi'])): ?>
                                    <a href="../assets/uploads/publikasi/<?= htmlspecialchars($p['file_publikasi']); ?>" 
                                       target="_blank" 
                                       class="btn-icon-soft pdf" 
                                       title="Download File">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($p['link_publikasi'])): ?>
                                    <a href="<?= htmlspecialchars($p['link_publikasi']); ?>" 
                                       target="_blank" 
                                       class="btn-icon-soft link" 
                                       title="Buka Link">
                                        <i class="bi bi-globe"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (empty($p['file_publikasi']) && empty($p['link_publikasi'])): ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td class="text-center pe-4">
                            <div class="dropdown">
                                <button class="btn-action" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="edit_publikasi.php?id=<?= $p['id']; ?>">
                                            <i class="bi bi-pencil-square me-2 text-primary opacity-75"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item text-danger d-flex align-items-center" 
                                           onclick="return confirm('Hapus publikasi ini permanen?')"
                                           href="publikasiAdmin.php?delete=<?= $p['id']; ?>">
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
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-light rounded-circle p-4 mb-3">
                                    <i class="bi bi-file-earmark-text text-muted display-4"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Belum ada publikasi</h6>
                                <p class="text-muted small">Mulai tambahkan jurnal atau paper ilmiah di sini.</p>
                                <a href="tambah_publikasi.php" class="btn btn-sm btn-primary-custom mt-2">Tambah Data</a>
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