<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

require_once "../config/database.php";

// --- LOGIKA DELETE ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Ambil nama file foto dulu untuk dihapus dari folder
    $qImg = pg_query($conn, "SELECT foto FROM struktur_organisasi WHERE id=$id");
    
    if ($qImg) {
        $rowImg = pg_fetch_assoc($qImg);
        // Hapus file fisik jika ada (path: assets/img/tim/)
        if ($rowImg && !empty($rowImg['foto']) && file_exists("../assets/img/tim/" . $rowImg['foto'])) {
            unlink("../assets/img/tim/" . $rowImg['foto']);
        }
    }

    // Hapus data dari database
    pg_query($conn, "DELETE FROM struktur_organisasi WHERE id = $id");

    header("Location: timAdmin.php?hapus=1");
    exit;
}

// Ambil semua data tim, urutkan berdasarkan urutan atau id
$query = "SELECT * FROM struktur_organisasi ORDER BY urutan ASC, id ASC";
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Tim Lab | Admin Lab AI</title>

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

    /* --- COMPONENTS KHUSUS TIM --- */
    .avatar-table {
        width: 50px; height: 50px; object-fit: cover;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .avatar-placeholder {
        width: 50px; height: 50px; border-radius: 50%;
        background: linear-gradient(135deg, #e0e7ff, #f3f4f6);
        color: var(--primary-color);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .badge-role {
        background-color: #f0fdf4; color: #15803d;
        padding: 6px 12px; border-radius: 8px;
        font-weight: 700; font-size: 0.8rem;
        border: 1px solid #dcfce7;
        white-space: nowrap; 
        display: inline-block;
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
                <h2 class="page-title display-6 mb-1">Manajemen Tim Lab</h2>
                <p class="text-muted mb-0">Kelola data dosen, staf, dan asisten Lab AI.</p>
            </div>
            
            <div>
                <a href="tambah_tim.php" class="btn btn-primary-custom">
                    <i class="bi bi-person-plus-fill"></i> 
                    <span>Tambah Anggota</span>
                </a>
            </div>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-custom alert-dismissible fade show p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                <div class="fw-medium">Data anggota berhasil dihapus.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>
        <?php if (isset($_GET['add'])) { ?>
            <div class="alert alert-custom alert-dismissible fade show p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                <div class="fw-medium">Data anggota berhasil ditambahkan.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>
        <?php if (isset($_GET['update'])) { ?>
            <div class="alert alert-custom alert-dismissible fade show p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                <div class="fw-medium">Data anggota berhasil diperbarui.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Profil</th>
                        <th>Nama Lengkap</th>
                        <th>Jabatan</th>
                        <th>Email</th>
                        <th>Pendidikan</th>
                        <th class="text-center pe-4">Menu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (pg_num_rows($result) > 0) {
                        $no = 1; 
                        while ($row = pg_fetch_assoc($result)) { 
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                        
                        <td>
                            <?php if(!empty($row['foto'])): ?>
                                <img src="../assets/img/tim/<?= htmlspecialchars($row['foto']) ?>" class="avatar-table" alt="Foto">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <i class="bi bi-person"></i>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama']); ?></span>
                        </td>

                        <td>
                            <span class="badge-role"><?= htmlspecialchars($row['jabatan']); ?></span>
                        </td>

                        <td>
                            <span class="text-muted small"><?= htmlspecialchars($row['email']); ?></span>
                        </td>

                        <td>
                            <span class="text-dark fw-medium small"><?= htmlspecialchars($row['pendidikan']); ?></span>
                        </td>

                        <td class="text-center pe-4">
                            <div class="dropdown">
                                <button class="btn-action" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="edit_tim.php?id=<?= $row['id']; ?>" class="dropdown-item d-flex align-items-center">
                                            <i class="bi bi-pencil-square me-2 text-primary opacity-75"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a onclick="return confirm('Yakin hapus anggota ini?')" 
                                           href="timAdmin.php?delete=<?= $row['id']; ?>" 
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
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-light rounded-circle p-4 mb-3">
                                    <i class="bi bi-people text-muted display-4"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Belum ada anggota tim</h6>
                                <p class="text-muted small">Tambahkan dosen atau mahasiswa ke dalam struktur organisasi.</p>
                                <a href="tambah_tim.php" class="btn btn-sm btn-primary-custom mt-2">Tambah Anggota</a>
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