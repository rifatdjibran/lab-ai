<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

if (isset($_POST['submit'])) {
    $judul = pg_escape_string($conn, $_POST['judul']);
    $penulis = pg_escape_string($conn, $_POST['penulis']);
    $isi = pg_escape_string($conn, $_POST['isi']);
    $admin_id = $_SESSION['admin_id'];

    $upload_dir = "../assets/uploads/berita/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $gambar = null;

    if (!empty($_FILES['gambar']['name'])) {
        $clean_name = preg_replace("/[^A-Za-z0-9.\-_]/", "_", $_FILES['gambar']['name']);
        $nama_file = time() . "_" . $clean_name;
        $path = $upload_dir . $nama_file;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $path)) {
            $gambar = $nama_file;
        }
    }

    pg_query($conn, "
        INSERT INTO berita(judul, isi, gambar, penulis, admin_id, tanggal)
        VALUES ('$judul', '$isi', '$gambar', '$penulis', '$admin_id', NOW())
    ");

    header("Location: beritaAdmin.php?add=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Berita | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Tambah Berita</h2>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Judul Berita</label>
            <input type="text" name="judul" class="input" required>

            <label class="fw-bold mt-3">Penulis</label>
            <input type="text" name="penulis" class="input" required>

            <label class="fw-bold mt-3">Isi Berita</label>
            <textarea name="isi" rows="7" class="input" required></textarea>

            <label class="fw-bold mt-3">Gambar</label>
            <input type="file" name="gambar" class="input-file" accept="image/*">

            <div class="button-row">

                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Simpan</span>
                </button>

                <a href="beritaAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>

            </div>

        </form>

    </div>
</div>

</body>
</html>