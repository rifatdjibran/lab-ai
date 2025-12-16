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

    $nama = pg_escape_string($conn, $_POST['nama']);
    $jabatan = pg_escape_string($conn, $_POST['jabatan']);
    $email = pg_escape_string($conn, $_POST['email']); 
    $pendidikan = pg_escape_string($conn, $_POST['pendidikan']); 
    $urutan = intval($_POST['urutan']);
    $admin_id = $_SESSION['admin_id'];

    // LOGIKA UPLOAD FOTO
    $foto = "";
    if (isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != "") {
        $target_dir = "../assets/img/tim/";
        
        // Buat folder jika belum ada
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowed)) {
            $new_name = time() . "_" . rand(1000, 9999) . "." . $ext;
            
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name)) {
                $foto = $new_name;
            } else {
                echo "<script>alert('Gagal upload gambar!');</script>";
            }
        } else {
            echo "<script>alert('Format gambar tidak valid!');</script>";
        }
    }

    // QUERY INSERT KE struktur_organisasi
    $query = "INSERT INTO struktur_organisasi(nama, jabatan, email, pendidikan, foto, urutan, created_at, admin_id)
              VALUES ('$nama', '$jabatan', '$email', '$pendidikan', '$foto', $urutan, NOW(), $admin_id)";

    if(pg_query($conn, $query)){
        header("Location: timAdmin.php?add=1");
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan data ke database!');</script>";
        die("Error: " . pg_last_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota Tim | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Tambah Anggota Tim</h2>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Nama Lengkap & Gelar</label>
            <input type="text" name="nama" class="input" required placeholder="Contoh: Ir. Budi Santoso, M.Kom">

            <label class="fw-bold">Jabatan</label>
            <input type="text" name="jabatan" class="input" required placeholder="Contoh: Ketua Laboratorium / Anggota">

            <label class="fw-bold">Email Kontak</label>
            <input type="email" name="email" class="input" required placeholder="contoh@domain.com">

            <label class="fw-bold">Pendidikan Terakhir</label>
            <input type="text" name="pendidikan" class="input" required placeholder="Contoh: S3 Teknik Informatika / S2 Ilmu Komputer">

            <label class="fw-bold">Urutan Tampil (Opsional)</label>
            <input type="number" name="urutan" class="input" value="1">

            <label class="fw-bold">Foto Profil</label>
            <input type="file" name="foto" class="input-file" accept="image/*">
            <small class="text-muted d-block mt-1">*Disarankan rasio 1:1 (kotak)</small>

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Simpan</span>
                </button>

                <a href="timAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>

    </div>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>