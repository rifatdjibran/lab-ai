<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

require_once "../config/database.php";

// LOGIKA HAPUS FASILITAS
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Amankan ID

    // Ambil file gambar lama untuk dihapus
    $q = pg_query($conn, "SELECT gambar FROM fasilitas WHERE id = $id");
    $data = pg_fetch_assoc($q);

    if ($data && !empty($data['gambar'])) {
        $file = "../assets/uploads/fasilitas/" . $data['gambar'];
        if (file_exists($file)) {
            unlink($file);
        }
    }

    // Hapus data dari database
    $del = pg_query($conn, "DELETE FROM fasilitas WHERE id = $id");

    if (!$del) {
        die("Gagal menghapus data: " . pg_last_error($conn));
    }

    header("Location: fasilitasAdmin.php?hapus=1");
    exit;
}

// AMBIL SEMUA DATA FASILITAS
$query = "SELECT * FROM fasilitas ORDER BY created_at DESC";
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Fasilitas | Admin Lab AI</title>

  <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
  <link rel="stylesheet" href="../assets/css/admin.css">
  
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
   

  <style>
    /* =========================================================
       INTERNAL CSS - TEMA KONSISTEN
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

    /* --- COMPONENTS KHUSUS FASILITAS --- */
    .table-img {
        width: 60px; height: 60px; object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border: 2px solid #fff;
    }
    
    .table-img-placeholder {
        width: 60px; height: 60px; border-radius: 12px;
        background-color: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        color: #cbd5e1; font-size: 1.5rem;
    }

    .badge-category {
        background-color: #e0e7ff; color: #4361ee;
        padding: 6px 12px; border-radius: 8px;
        font-weight: 700; font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    /* --- SOLUSI AGAR TIDAK NABRAK SIDEBAR --- */
    .deskripsi-limit {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Maksimal 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        
        /* Penting: Batasi lebar agar wrap bekerja */
        max-width: 300px; 
        
        line-height: 1.5;
        color: #6c757d;
        font-size: 0.9rem;
    }

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
                <h2 class="page-title display-6 mb-1">Manajemen Fasilitas</h2>
                <p class="text-muted mb-0">Kelola inventaris dan sarana prasarana Lab AI.</p>
            </div>
            
            <div>
                <a href="tambah_fasilitas.php" class="btn btn-primary-custom">
                    <i class="bi bi-plus-lg"></i> 
                    <span>Tambah Fasilitas</span>
                </a>
            </div>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-custom alert-dismissible fade show p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                <div class="fw-medium">Fasilitas berhasil dihapus.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="ps-4" width="5%">No</th>
                        <th width="10%">Gambar</th>
                        <th width="20%">Nama Fasilitas</th>
                        <th width="15%">Kategori</th>
                        <th width="35%">Deskripsi</th>
                        <th class="text-center pe-4" width="10%">Menu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (pg_num_rows($result) > 0) {
                        $no = 1; 
                        while ($f = pg_fetch_assoc($result)) { 
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                        
                        <td>
                            <?php if (!empty($f['gambar'])): ?>
                                <img src="../assets/uploads/fasilitas/<?= htmlspecialchars($f['gambar']); ?>" class="table-img" alt="Foto">
                            <?php else: ?>
                                <div class="table-img-placeholder">
                                    <i class="bi bi-pc-display"></i>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($f['nama_fasilitas']); ?></span>
                        </td>

                        <td>
                            <span class="badge-category"><?= htmlspecialchars($f['kategori']); ?></span>
                        </td>

                        <td>
                            <div class="deskripsi-limit" title="<?= htmlspecialchars($f['deskripsi']); ?>">
                                <?= htmlspecialchars($f['deskripsi']); ?>
                            </div>
                        </td>

                        <td class="text-center pe-4">
                            <div class="dropdown">
                                <button class="btn-action" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="edit_fasilitas.php?id=<?= $f['id']; ?>" class="dropdown-item d-flex align-items-center">
                                            <i class="bi bi-pencil-square me-2 text-primary opacity-75"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a onclick="return confirm('Hapus fasilitas ini?')" 
                                           href="fasilitasAdmin.php?delete=<?= $f['id']; ?>" 
                                           class="dropdown-item text-danger d-flex align-items-center">
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
                                    <i class="bi bi-cpu text-muted display-4"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Belum ada fasilitas</h6>
                                <p class="text-muted small">Tambahkan inventaris alat dan fasilitas lab di sini.</p>
                                <a href="tambah_fasilitas.php" class="btn btn-sm btn-primary-custom mt-2">Tambah Fasilitas</a>
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