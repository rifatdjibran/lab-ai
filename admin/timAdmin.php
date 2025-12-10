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
        if ($rowImg && $rowImg['foto'] && file_exists("../assets/img/tim/" . $rowImg['foto'])) {
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

// Cek error query
if (!$result) {
    die("Error Database: " . pg_last_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tim Lab | Lab AI Admin</title>

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

        <h3 class="fw-bold mb-3">Manajemen Tim Laboratorium</h3>
        <p class="text-muted mb-4">Kelola data dosen dan anggota tim Lab AI.</p>

        <div class="d-flex justify-content-between mb-4">
            <a href="tambah_tim.php" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Anggota
            </a>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-danger">Data anggota berhasil dihapus!</div>
        <?php } ?>
        <?php if (isset($_GET['add'])) { ?>
            <div class="alert alert-success">Data anggota berhasil ditambahkan!</div>
        <?php } ?>
        <?php if (isset($_GET['update'])) { ?>
            <div class="alert alert-success">Data anggota berhasil diperbarui!</div>
        <?php } ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-modern align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Lengkap</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no=1; while ($row = pg_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>

                            <td>
                                <?php if(!empty($row['foto'])): ?>
                                    <img src="../assets/img/tim/<?= $row['foto'] ?>" width="50" height="50" class="object-fit-cover rounded-circle border">
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Img</span>
                                <?php endif; ?>
                            </td>

                            <td class="fw-semibold"><?= htmlspecialchars($row['nama']); ?></td>

                            <td><?= htmlspecialchars($row['jabatan']); ?></td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="more-btn" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="edit_tim.php?id=<?= $row['id']; ?>" class="dropdown-item">
                                                <i class="bi bi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a onclick="return confirm('Yakin hapus anggota ini?')" 
                                               href="timAdmin.php?delete=<?= $row['id']; ?>" 
                                               class="dropdown-item text-danger">
                                               <i class="bi bi-trash me-2"></i> Hapus
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
<script>
    const toggleBtn = document.getElementById("toggleSidebar");
    if(toggleBtn){
        toggleBtn.addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("collapsed");
            document.getElementById("main-content").classList.toggle("collapsed");
        });
    }
</script>
</body>
</html>