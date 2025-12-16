<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

require_once "../config/database.php";

// ===============================
//  SUBMIT FORM
// ===============================
if (isset($_POST['submit'])) {

    $judul = pg_escape_string($conn, $_POST['judul']);
    $peneliti = pg_escape_string($conn, $_POST['peneliti']);
    $tahun = pg_escape_string($conn, $_POST['tahun']);
    $desk = pg_escape_string($conn, $_POST['deskripsi']);
    $admin_id = $_SESSION['admin_id'];

    pg_query($conn, "
        INSERT INTO penelitian(judul, peneliti, tahun, deskripsi, created_at, admin_id)
        VALUES ('$judul', '$peneliti', '$tahun', '$desk', NOW(), $admin_id)
    ");

    header("Location: penelitianAdmin.php?add=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penelitian | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>


<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Tambah Penelitian</h2>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Judul Penelitian</label>
            <input type="text" name="judul" class="input" required>

            <label class="fw-bold mt-3">Peneliti</label>
            <input type="text" name="peneliti" class="input" required>

            <label class="fw-bold mt-3">Tahun</label>
            <input type="number" name="tahun" class="input" min="1900" max="2100" required>

            <label class="fw-bold mt-3">Deskripsi</label>
            <textarea name="deskripsi" rows="6" class="input" required></textarea>

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Simpan</span>
                </button>

                <a href="penelitianAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>

    </div>
</div>


</body>
</html>
