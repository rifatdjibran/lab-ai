<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

// Konfigurasi Paginasi
$limit = 10; // Maksimum data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil total data untuk menghitung jumlah halaman
$total_result = pg_query($conn, "SELECT COUNT(*) AS total FROM karya_dosen");
$total_rows = pg_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_rows / $limit);

// LOGIKA HAPUS KARYA
if (isset($_GET['delete'])) {
    $id_karya = intval($_GET['delete']);

    // Hapus data
    $result = pg_query_params($conn, "DELETE FROM karya_dosen WHERE id_karya = $1", array($id_karya));

    if ($result) {
        // Redirect dengan menyertakan halaman saat ini
        header("Location: karyaAdmin.php?hapus=1&page=" . $page);
        exit();
    } else {
        header("Location: karyaAdmin.php?hapus=0&page=" . $page);
        exit();
    }
}

// AMBIL DATA KARYA (JOIN dengan struktur_organisasi untuk nama dosen) DENGAN LIMIT DAN OFFSET
$sql_data = "SELECT kd.*, so.nama 
             FROM karya_dosen kd
             LEFT JOIN struktur_organisasi so ON kd.id_anggota = so.id 
             ORDER BY kd.tahun DESC, kd.jenis_karya ASC
             LIMIT $limit OFFSET $offset"; // Tambahkan LIMIT dan OFFSET
             
$result = pg_query($conn, $sql_data);

// Hitung nomor awal untuk baris saat ini
$no_start = $offset + 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Karya Dosen | Admin Lab AI</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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
        .table-container { 
            padding-bottom: 50px; 
            overflow-x: auto; /* Tambahkan overflow-x untuk keamanan */
        }

        .custom-table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0 15px; 
            min-width: 900px;
        }

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

        /* --- COMPONENTS KHUSUS KARYA --- */
        .badge-year {
            background-color: #f0f3ff; color: var(--primary-color);
            padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 0.85rem;
        }

        /* Badge Jenis Karya Logic */
        .badge-type {
            padding: 6px 12px; border-radius: 6px; font-size: 0.75rem;
            font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .type-riset { background-color: #e0e7ff; color: #4361ee; } 
        .type-haki { background-color: #f3e8ff; color: #7e22ce; } 
        .type-ppm { background-color: #dcfce7; color: #15803d; } 
        .type-buku { background-color: #ffedd5; color: #c2410c; } 
        .type-default { background-color: #f1f5f9; color: #64748b; } 

        .judul-limit {
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden; text-overflow: ellipsis; max-width: 350px; line-height: 1.5;
        }

        .btn-action {
            width: 35px; height: 35px; border-radius: 50%; border: none;
            background: transparent; color: var(--text-muted);
            transition: all 0.2s; display: flex; align-items: center; justify-content: center; margin: 0 auto;
            /* Tambahan untuk menghilangkan border menu dot */
            outline: none !important; 
            box-shadow: none !important;
        }
        .btn-action:focus, .btn-action:active {
            outline: none !important;
            box-shadow: none !important;
        }
        .btn-action:hover { background: #f4f7fe; color: var(--primary-color); }
        
        /* Menyembunyikan ikon panah default dropdown Bootstrap */
        .dropdown-toggle::after {
            display: none; 
        }


        .dropdown-menu { border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border-radius: 12px; padding: 8px; }
        .dropdown-item { border-radius: 8px; font-size: 0.9rem; padding: 8px 12px; color: var(--text-main); font-weight: 500; }
        .dropdown-item:hover { background-color: #f4f7fe; color: var(--primary-color); }

        /* Alert */
        .alert-custom { background: white; border: none; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); color: var(--text-main); }
        
        /* Paginasi Kustom (Disalin dari publikasiAdmin) */
        .pagination-custom .page-link {
            background-color: white;
            border: 1px solid #e2e8f0;
            color: var(--text-main);
            border-radius: 10px;
            margin: 0 4px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .pagination-custom .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        .pagination-custom .page-link:hover {
            background-color: #f4f7fe;
            border-color: #cbd5e1;
            color: var(--primary-color);
        }
        .pagination-custom .page-item.disabled .page-link {
            background-color: #f8fafc;
            color: var(--text-muted);
            border-color: #e2e8f0;
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
                <h2 class="page-title display-6 mb-1">Karya Dosen</h2>
                <p class="text-muted mb-0">Kelola data Riset, HAKI, PPM, dan Aktivitas anggota. </p>
                <p class="text-muted mb-0">Total: <?= $total_rows ?> data</p>
            </div>
            
            <div>
                <a href="tambah_karya.php" class="btn btn-primary-custom">
                    <i class="bi bi-plus-lg"></i> 
                    <span>Tambah Karya</span>
                </a>
            </div>
        </div>

        <?php if (isset($_GET['hapus']) && $_GET['hapus'] == 1) { ?>
            <div class="alert alert-custom alert-dismissible fade show p-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                <div class="fw-medium">Data karya berhasil dihapus.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="ps-4" width="5%">No</th>
                        <th width="20%">Anggota</th>
                        <th width="15%">Jenis Karya</th>
                        <th width="35%">Judul Karya</th>
                        <th width="10%">Tahun</th>
                        <th width="15%">No. Dokumen</th>
                        <th class="text-center pe-4">Menu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (pg_num_rows($result) > 0) {
                        $no = $no_start; // Mulai dari nomor awal halaman
                        while ($karya = pg_fetch_assoc($result)) { 
                            // Tentukan warna badge berdasarkan jenis karya
                            $jenis = strtolower($karya['jenis_karya']);
                            if (strpos($jenis, 'riset') !== false || strpos($jenis, 'penelitian') !== false) {
                                $badgeClass = 'type-riset';
                            } elseif (strpos($jenis, 'haki') !== false) {
                                $badgeClass = 'type-haki';
                            } elseif (strpos($jenis, 'ppm') !== false || strpos($jenis, 'pengabdian') !== false) {
                                $badgeClass = 'type-ppm';
                            } elseif (strpos($jenis, 'buku') !== false) {
                                $badgeClass = 'type-buku';
                            } else {
                                $badgeClass = 'type-default';
                            }
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                        
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 text-primary" style="width:35px; height:35px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <span class="fw-bold text-dark small">
                                    <?= !empty($karya['nama']) ? htmlspecialchars($karya['nama']) : '<span class="text-muted fst-italic">Unknown</span>'; ?>
                                </span>
                            </div>
                        </td>

                        <td>
                            <span class="badge-type <?= $badgeClass ?>"><?= htmlspecialchars($karya['jenis_karya']); ?></span>
                        </td>

                        <td>
                            <div class="judul-limit fw-medium text-dark" title="<?= htmlspecialchars($karya['judul']); ?>">
                                <?= htmlspecialchars($karya['judul']); ?>
                            </div>
                        </td>

                        <td>
                            <span class="badge-year"><?= htmlspecialchars($karya['tahun']); ?></span>
                        </td>

                        <td>
                            <?php if(!empty($karya['nomor_dokumen'])): ?>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-hash me-1"></i>
                                    <?= htmlspecialchars($karya['nomor_dokumen']); ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center pe-4">
                            <div class="dropdown">
                                <button class="btn-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="edit_karya.php?id=<?= $karya['id_karya']; ?>" class="dropdown-item d-flex align-items-center">
                                            <i class="bi bi-pencil-square me-2 text-primary opacity-75"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a onclick="return confirm('Hapus karya ini?')" 
                                            href="karyaAdmin.php?delete=<?= $karya['id_karya']; ?>" 
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
                                    <i class="bi bi-journal-album text-muted display-4"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Belum ada karya dosen</h6>
                                <p class="text-muted small">Tambahkan data HAKI, Riset, atau Pengabdian Masyarakat.</p>
                                <a href="tambah_karya.php" class="btn btn-sm btn-primary-custom mt-2">Tambah Karya</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($total_pages > 1) { ?>
            <nav class="d-flex justify-content-center mt-4">
                <ul class="pagination pagination-custom">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="karyaAdmin.php?page=<?= $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    
                    <?php 
                    // Tampilkan tombol halaman di sekitar halaman saat ini
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);

                    // Tambahkan elipsis jika perlu
                    if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="karyaAdmin.php?page=1">1</a></li>';
                        if ($start_page > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $start_page; $i <= $end_page; $i++) { 
                    ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="karyaAdmin.php?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php 
                    } 
                    
                    // Tambahkan elipsis jika perlu
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="karyaAdmin.php?page=' . $total_pages . '">' . $total_pages . '</a></li>';
                    }
                    ?>

                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="karyaAdmin.php?page=<?= $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php } ?>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>