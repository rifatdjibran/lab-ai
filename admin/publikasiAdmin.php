<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

// DELETE PUBLIKASI
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // ambil file publikasi
    $q = pg_query($conn, "SELECT file_publikasi FROM publikasi WHERE id = $id");
    $file = pg_fetch_assoc($q)['file_publikasi'];

    // hapus file jika ada
    if (!empty($file) && file_exists("../assets/uploads/publikasi/" . $file)) {
        unlink("../assets/uploads/publikasi/" . $file);
    }

    // hapus data dari database
    pg_query($conn, "DELETE FROM publikasi WHERE id = $id");

    header("Location: publikasiAdmin.php?hapus=1");
    exit;
}

// Ambil semua publikasi
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
    <title>Manajemen Publikasi | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Memastikan tabel dapat di-scroll horizontal jika terlalu lebar */
        .table-responsive {
            overflow-x: auto;
        }
        
        /* Gaya dasar untuk sel yang harus dipotong/dikecilkan */
        .truncate-cell {
            /* Memaksa teks satu baris */
            white-space: nowrap; 
            overflow: hidden; 
            /* Menampilkan elipsis (...) di akhir teks yang terpotong */
            text-overflow: ellipsis; 
            vertical-align: top;
            padding-top: 15px; /* Sedikit padding agar tidak terlalu mepet */
        }
        
        /* Batasi Lebar Kolom Tertentu (Judul, Penulis, Jurnal) */
        .col-judul {
            max-width: 180px; /* Dikecilkan */
            min-width: 150px; 
        }
        .col-penulis, .col-jurnal {
            max-width: 120px; /* Dikecilkan */
            min-width: 80px;
        }

        /* Kolom Aksi dan File diset agar tidak terlalu lebar */
        .col-aksi, .col-file, .col-tahun {
            width: 80px;
            min-width: 80px;
            white-space: nowrap;
        }

    </style>
    </head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="d-flex">
    <?php include "../includes/sidebar.php"; ?>

    <div id="main-content" class="p-4 flex-grow-1">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive"> 
                    <table class="table table-modern align-middle w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th class="col-file">File</th>
                                <th class="col-judul">Judul</th>
                                <th>Penulis Utama (Lab)</th>
                                <th class="col-penulis">Penulis (Teks)</th>
                                <th class="col-jurnal">Jurnal</th>
                                <th class="col-tahun">Tahun</th>
                                <th>Kategori</th>
                                <th>Link</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $no=1; while ($p = pg_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="col-file text-center">
                                    <?php if (!empty($p['file_publikasi'])) { ?>
                                        <a href="../assets/uploads/publikasi/<?= $p['file_publikasi']; ?>" 
                                            class="btn btn-sm btn-outline-primary"
                                            target="_blank">
                                            Lihat File
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-muted">Tidak ada file</span>
                                    <?php } ?>
                                </td>

                                <td class="fw-semibold col-judul truncate-cell" title="<?= htmlspecialchars($p['judul']); ?>">
                                    <?= $p['judul']; ?>
                                </td>

                                <td>
                                    <?= $p['id_anggota_utama'] ? '<span class="badge bg-success">' . htmlspecialchars($p['nama_anggota_utama']) . '</span>' : '<span class="text-muted">Eksternal</span>' ?>
                                </td>

                                <td class="col-penulis truncate-cell" title="<?= htmlspecialchars($p['penulis']); ?>">
                                    <?= $p['penulis']; ?>
                                </td>

                                <td class="col-jurnal truncate-cell" title="<?= htmlspecialchars($p['jurnal']); ?>">
                                    <?= $p['jurnal']; ?>
                                </td>

                                <td class="col-tahun"><?= $p['tahun']; ?></td>

                                <td><?= $p['kategori']; ?></td>

                                <td>
                                    <?php if (!empty($p['link_publikasi'])) { ?>
                                        <a href="<?= $p['link_publikasi']; ?>" target="_blank">Lihat</a>
                                    <?php } else { ?>
                                        <span class="text-muted">Tidak ada</span>
                                    <?php } ?>
                                </td>

                                <td class="col-aksi text-center">
                                    <div class="dropdown">
                                        <button class="more-btn" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="edit_publikasi.php?id=<?= $p['id']; ?>" class="dropdown-item">
                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a onclick="return confirm('Hapus publikasi ini?')" 
                                                    href="publikasiAdmin.php?delete=<?= $p['id']; ?>" 
                                                    class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i>Hapus
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>