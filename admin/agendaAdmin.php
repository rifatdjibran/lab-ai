<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

require_once "../config/database.php";

// DELETE KEGIATAN
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $q = pg_query($conn, "SELECT gambar FROM kegiatan WHERE id = $id");
    $g = pg_fetch_assoc($q)['gambar'];

    if (!empty($g) && file_exists("../assets/uploads/kegiatan/" . $g)) {
        unlink("../assets/uploads/kegiatan/" . $g);
    }

    pg_query($conn, "DELETE FROM kegiatan WHERE id = $id");

    header("Location: agendaAdmin.php?hapus=1");
    exit;
}

$result = pg_query($conn, "SELECT * FROM kegiatan ORDER BY tanggal_mulai ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Agenda | Lab AI Admin</title>

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

        <h3 class="fw-bold mb-3">Manajemen Agenda</h3>
        <p class="text-muted mb-4">Kelola agenda & kegiatan Lab AI.</p>

        <div class="d-flex justify-content-between mb-4">
            <a href="tambah_agenda.php" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Agenda
            </a>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-danger">Agenda berhasil dihapus!</div>
        <?php } ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-modern align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Kegiatan</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; while ($a = pg_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <?php if ($a['gambar']) { ?>
                                    <img src="../assets/uploads/kegiatan/<?= $a['gambar']; ?>" style="width:70px;border-radius:6px;">
                                <?php } ?>
                            </td>

                            <td class="fw-semibold"><?= $a['nama_kegiatan']; ?></td>
                            <td><?= $a['lokasi']; ?></td>

                            <td>
                                <?= date('d M Y', strtotime($a['tanggal_mulai'])); ?> -
                                <?= date('d M Y', strtotime($a['tanggal_selesai'])); ?>
                            </td>

                            <td>
                                <?php
                                    $today = date('Y-m-d');
                                    if ($today < $a['tanggal_mulai']) echo "<span class='badge bg-warning text-dark'>Segera</span>";
                                    else if ($today <= $a['tanggal_selesai']) echo "<span class='badge bg-success'>Berlangsung</span>";
                                    else echo "<span class='badge bg-secondary'>Selesai</span>";
                                ?>
                            </td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="more-btn" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="edit_agenda.php?id=<?= $a['id']; ?>" class="dropdown-item"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li><a onclick="return confirm('Hapus agenda ini?')" href="agendaAdmin.php?delete=<?= $a['id']; ?>" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Hapus</a></li>
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
