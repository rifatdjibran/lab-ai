<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

if (isset($_POST['submit'])) {
    $nama = pg_escape_string($conn, $_POST['nama']);
    $deskripsi = pg_escape_string($conn, $_POST['deskripsi']);
    $kategori = pg_escape_string($conn, $_POST['kategori']);
    $admin_id = $_SESSION['admin_id'];

    // Folder upload
    $upload_dir = "../assets/uploads/fasilitas/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $gambar = null;

    // Upload file jika ada
    if (!empty($_FILES['gambar']['name'])) {

        // Bersihkan nama file
        $clean_name = preg_replace("/[^A-Za-z0-9.\-_]/", "_", $_FILES['gambar']['name']);
        $nama_file = time() . "_" . $clean_name;

        $path = $upload_dir . $nama_file;

        // Pindahkan file
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $path)) {
            $gambar = $nama_file;
        }
    }

    // QUERY INSERT BENAR
    $sql = "
        INSERT INTO fasilitas (nama_fasilitas, deskripsi, gambar, kategori, admin_id, created_at)
        VALUES ('$nama', '$deskripsi', '$gambar', '$kategori', '$admin_id', NOW())
    ";

    $insert = pg_query($conn, $sql);

    // Cek error
    if (!$insert) {
        die('Gagal menambahkan fasilitas: ' . pg_last_error($conn));
    }

    header("Location: fasilitasAdmin.php?add=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Fasilitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5 p-4 bg-white rounded shadow">
    <h3 class="fw-bold mb-3">Tambah Fasilitas</h3>

    <form method="POST" enctype="multipart/form-data">

        <label class="fw-bold mt-2">Nama Fasilitas</label>
        <input type="text" name="nama" class="input" required>

        <label class="fw-bold mt-3">Deskripsi</label>
        <textarea name="deskripsi" class="input" rows="4" required></textarea>

        <label class="fw-bold mt-3">Kategori</label>
        <select name="kategori" class="input" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Lab Komputer">Lab Komputer</option>
            <option value="Ruang Kelas">Ruang Kelas</option>
            <option value="Peralatan">Peralatan</option>
        </select>

        <label class="fw-bold mt-3">Gambar</label>
        <input type="file" name="gambar" class="input" accept="image/*">

        <button type="submit" name="submit" class="btn btn-primary mt-4">Simpan</button>
        <a href="fasilitasAdmin.php" class="btn btn-secondary mt-4">Kembali</a>

    </form>
</div>

</body>
</html>
