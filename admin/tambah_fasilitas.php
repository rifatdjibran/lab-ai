<?php
session_start();
// Cek sesi admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

// --- LOGIKA DATABASE (Tetap milik Fasilitas) ---
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

    // Query Insert Fasilitas
    $sql = "
        INSERT INTO fasilitas (nama_fasilitas, deskripsi, gambar, kategori, admin_id, created_at)
        VALUES ('$nama', '$deskripsi', '$gambar', '$kategori', '$admin_id', NOW())
    ";

    $insert = pg_query($conn, $sql);

    // Cek error
    if (!$insert) {
        die('Gagal menambahkan fasilitas: ' . pg_last_error($conn));
    }

    // Redirect setelah sukses
    header("Location: fasilitasAdmin.php?add=1");
    exit;
}
// --- BATAS LOGIKA DATABASE ---
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasilitas | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Tambah Fasilitas</h2>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Nama Fasilitas</label>
            <input type="text" name="nama" class="input" required>

            <label class="fw-bold mt-3">Kategori</label>
            <select name="kategori" class="input" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Lab Komputer">Lab Komputer</option>
                <option value="Ruang Kelas">Ruang Kelas</option>
                <option value="Peralatan">Peralatan</option>
            </select>

            <label class="fw-bold mt-3">Deskripsi</label>
            <textarea name="deskripsi" rows="7" class="input" required></textarea>

            <label class="fw-bold mt-3">Gambar</label>
            <input type="file" name="gambar" class="input-file" accept="image/*">

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Simpan</span>
                </button>

                <a href="fasilitasAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>

    </div>
</div>

</body>
</html>