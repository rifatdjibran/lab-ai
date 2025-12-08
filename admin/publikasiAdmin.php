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
$result = pg_query($conn, "SELECT * FROM publikasi ORDER BY tahun DESC");
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
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="d-flex">
    <?php include "../includes/sidebar.php"; ?>

    <div id="main-content" class="p-4 flex-grow-1">

        <h3 class="fw-bold mb-3">Manajemen Publikasi</h3>
        <p class="text-muted mb-4">Kelola data publikasi Lab AI.</p>

        <div class="d-flex justify-content-between mb-4">
            <a href="tambah_publikasi.php" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Publikasi
            </a>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-danger">Publikasi berhasil dihapus!</div>
        <?php } ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-modern align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>File</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Jurnal</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Link</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; while ($p = pg_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>

                            <!-- FILE -->
                            <td>
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

                            <!-- JUDUL -->
                            <td class="fw-semibold"><?= $p['judul']; ?></td>

                            <!-- PENULIS -->
                            <td><?= $p['penulis']; ?></td>

                            <!-- JURNAL -->
                            <td><?= $p['jurnal']; ?></td>

                            <!-- TAHUN -->
                            <td><?= $p['tahun']; ?></td>

                            <td><?= $p['kategori']; ?></td>

                            <!-- LINK -->
                            <td>
                                <?php if (!empty($p['link_publikasi'])) { ?>
                                    <a href="<?= $p['link_publikasi']; ?>" target="_blank">Lihat</a>
                                <?php } else { ?>
                                    <span class="text-muted">Tidak ada</span>
                                <?php } ?>
                            </td>

                            <!-- AKSI -->
                            <td class="text-center">
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

<script>
document.getElementById("toggleSidebar").addEventListener("click", () => {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("main-content");

    sidebar.classList.toggle("collapsed");
    content.classList.toggle("collapsed");
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
