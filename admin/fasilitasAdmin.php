<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

// HAPUS DATA FASILITAS
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']); // amankan id

    // Ambil file gambar
    $q = pg_query($conn, "SELECT gambar FROM fasilitas WHERE id = $id");
    $data = pg_fetch_assoc($q);

    if ($data && !empty($data['gambar'])) {
        $file = "../assets/uploads/fasilitas/" . $data['gambar'];

        // Jika file ada → hapus
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

if (!$result) {
    die("Gagal mengambil data fasilitas: " . pg_last_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Fasilitas | Lab AI Admin</title>

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

        <h3 class="fw-bold mb-3">Manajemen Fasilitas</h3>
        <p class="text-muted mb-4">Kelola fasilitas yang dimiliki Lab AI.</p>

        <div class="d-flex justify-content-between mb-4">
            <a href="tambah_fasilitas.php" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Fasilitas
            </a>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-danger">Fasilitas berhasil dihapus!</div>
        <?php } ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-modern align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Fasilitas</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no = 1; while ($f = pg_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>

                            <td>
                                <?php if (!empty($f['gambar'])) { ?>
                                    <img src="../assets/uploads/fasilitas/<?= $f['gambar'] ?>" 
                                         style="width: 80px; height: 60px; object-fit: cover;">
                                <?php } ?>
                            </td>

                            <td class="fw-semibold"><?= $f['nama_fasilitas']; ?></td>

                            <td><?= $f['kategori']; ?></td>

                            <td><?= substr($f['deskripsi'], 0, 50) . "..."; ?></td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="more-btn" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="edit_fasilitas.php?id=<?= $f['id']; ?>" class="dropdown-item">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a>
                                        </li>

                                        <li>
                                            <a onclick="return confirm('Hapus fasilitas ini?')" 
                                               href="fasilitasAdmin.php?delete=<?= $f['id']; ?>" 
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
