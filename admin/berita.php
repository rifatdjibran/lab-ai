<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}
require_once "../config/database.php";

// Hapus Berita
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $g = pg_query($conn, "SELECT gambar FROM berita WHERE id=$id");
    $gm = pg_fetch_assoc($g)['gambar'];

    if (!empty($gm) && file_exists("../uploads/" . $gm)) {
        unlink("../uploads/" . $gm);
    }

    pg_query($conn, "DELETE FROM berita WHERE id=$id");

    header("Location: berita.php?hapus=1");
    exit;
}

$result = pg_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Berita | Lab AI Admin</title>

  <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* Extra style untuk tabel */
    table img {
        border-radius: 6px;
    }
  </style>
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="d-flex">

  

  <!-- SIDEBAR -->
  <?php include "../includes/sidebar.php"; ?>

  <!-- MAIN CONTENT -->
  <div id="main-content" class="p-4 flex-grow-1">

    <h3 class="fw-bold mb-3">Manajemen Berita</h3>
    <p class="text-muted mb-4">Kelola data berita pada portal Lab AI.</p>

    <div class="d-flex justify-content-between mb-4">
        <a href="tambah_berita.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Berita
        </a>
    </div>

    <?php if (isset($_GET['hapus'])) { ?>
        <div class="alert alert-danger">Berita berhasil dihapus!</div>
    <?php } ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-modern align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:90px;">Gambar</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th style="width:150px;">Tanggal</th>
                        <th style="width:70px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php $no = 1; while ($b = pg_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <?php if ($b['gambar']) { ?>
                            <img src="../assets/uploads/berita/<?= $b['gambar']; ?>" class="table-img">
                        <?php } ?>
                    </td>

                    <td class="fw-semibold"><?= $b['judul']; ?></td>
                    <td><?= $b['penulis']; ?></td>
                    <td><?= $b['tanggal']; ?></td>

                    <td class="text-center">
                        <div class="dropdown">
                            <button class="more-btn" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="edit_berita.php?id=<?= $b['id']; ?>" 
                                class="dropdown-item">
                                    <i class="bi bi-pencil-square me-2"></i>Edit
                            </a>
                        </li>
                        <li>
                            <a onclick="return confirm('Hapus berita ini?')" 
                                href="berita.php?delete=<?= $b['id']; ?>" 
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
