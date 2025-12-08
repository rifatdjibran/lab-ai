<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

// DELETE PENELITIAN
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // hapus data dari database
    pg_query($conn, "DELETE FROM penelitian WHERE id = $id");

    header("Location: penelitianAdmin.php?hapus=1");
    exit;
}

// Ambil semua data penelitian
$result = pg_query($conn, "SELECT * FROM penelitian ORDER BY tahun DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Penelitian | Lab AI Admin</title>

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

        <h3 class="fw-bold mb-3">Manajemen Penelitian</h3>
        <p class="text-muted mb-4">Kelola data penelitian Lab AI.</p>

        <div class="d-flex justify-content-between mb-4">
            <a href="tambah_penelitian.php" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Penelitian
            </a>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-danger">Penelitian berhasil dihapus!</div>
        <?php } ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-modern align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Judul Penelitian</th>
                            <th>Peneliti</th>
                            <th>Tahun</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; while ($p = pg_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>

                            <!-- JUDUL -->
                            <td class="fw-semibold"><?= $p['judul']; ?></td>

                            <!-- PENELITI -->
                            <td><?= $p['peneliti']; ?></td>

                            <!-- TAHUN -->
                            <td><?= $p['tahun']; ?></td>

                            <!-- DESKRIPSI -->
                            <td><?= substr($p['deskripsi'], 0, 50) . "..."; ?></td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="more-btn" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="edit_penelitian.php?id=<?= $p['id']; ?>" class="dropdown-item">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>

                                        <li>
                                            <a onclick="return confirm('Hapus penelitian ini?')" 
                                               href="penelitianAdmin.php?delete=<?= $p['id']; ?>" 
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
