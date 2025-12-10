<?php
session_start();
// Cek sesi dan koneksi database
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once "../config/database.php";

// DELETE KARYA DOSEN
if (isset($_GET['delete'])) {
    $id_karya = intval($_GET['delete']);

    // Gunakan prepared statement untuk keamanan
    $sql_hapus = "DELETE FROM karya_dosen WHERE id_karya = $1";
    $result_hapus = pg_prepare($conn, "hapus_karya", $sql_hapus);
    
    if (pg_execute($conn, "hapus_karya", array($id_karya))) {
        header("Location: karyaAdmin.php?hapus=1");
        exit();
    } else {
        // Handle error jika diperlukan
        // $error = pg_last_error($conn);
        header("Location: karyaAdmin.php?hapus=0");
        exit();
    }
}

// Ambil semua data karya dosen (Riset, HAKI, PPM, Aktivitas)
// JOIN dengan struktur_organisasi untuk mendapatkan nama anggota
$sql_data = "SELECT kd.*, so.nama 
             FROM karya_dosen kd
             JOIN struktur_organisasi so ON kd.id_anggota = so.id -- asumsi PK di struktur_organisasi adalah 'id'
             ORDER BY kd.tahun DESC, kd.jenis_karya ASC";
             
$result = pg_query($conn, $sql_data);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Karya Dosen | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="d-flex">
    <?php include "../includes/sidebar.php"; ?>

    <div id="main-content" class="p-4 flex-grow-1">

        <h3 class="fw-bold mb-3">Manajemen Karya Dosen</h3>
        <p class="text-muted mb-4">Kelola data Riset, HAKI, PPM, dan Aktivitas anggota Lab AI.</p>

        <div class="d-flex justify-content-between mb-4">
            <a href="tambah_karya.php" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Karya Baru
            </a>
        </div>

        <?php if (isset($_GET['hapus']) && $_GET['hapus'] == 1) { ?>
            <div class="alert alert-danger">Data Karya Dosen berhasil dihapus!</div>
        <?php } elseif (isset($_GET['hapus']) && $_GET['hapus'] == 0) { ?>
            <div class="alert alert-danger">Gagal menghapus Data Karya Dosen!</div>
        <?php } ?>
        
        <?php if (isset($_GET['add'])) { ?>
            <div class="alert alert-success">Data Karya Dosen berhasil ditambahkan!</div>
        <?php } elseif (isset($_GET['update'])) { ?>
            <div class="alert alert-success">Data Karya Dosen berhasil diupdate!</div>
        <?php } ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-modern align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Anggota</th>
                            <th>Jenis</th>
                            <th>Judul</th>
                            <th>Tahun</th>
                            <th>No. Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; while ($karya = pg_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($karya['nama']); ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($karya['jenis_karya']); ?></span></td>
                            <td><?= htmlspecialchars($karya['judul']); ?></td>
                            <td><?= htmlspecialchars($karya['tahun']); ?></td>
                            <td>
                                <?= empty($karya['nomor_dokumen']) ? '<span class="text-muted">-</span>' : htmlspecialchars($karya['nomor_dokumen']) ?>
                            </td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="more-btn" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="edit_karya.php?id=<?= $karya['id_karya']; ?>" class="dropdown-item">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>

                                        <li>
                                            <a onclick="return confirm('Hapus karya ini?')" 
                                                href="karyaAdmin.php?delete=<?= $karya['id_karya']; ?>" 
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>