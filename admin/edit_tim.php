<?php
session_start();
require_once "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: timAdmin.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data lama dari struktur_organisasi
$q = pg_query($conn, "SELECT * FROM struktur_organisasi WHERE id = $id");
$data = pg_fetch_assoc($q);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

// PROSES UPDATE
if (isset($_POST['update'])) {

    $nama     = pg_escape_string($conn, $_POST['nama']);
    $jabatan  = pg_escape_string($conn, $_POST['jabatan']);
    $email      = pg_escape_string($conn, $_POST['email']); 
    $pendidikan = pg_escape_string($conn, $_POST['pendidikan']); 
    $urutan   = intval($_POST['urutan']);
    
    $foto = $data['foto']; // Default foto lama

    // Jika ada upload foto baru
    if (isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != "") {
        $target_dir = "../assets/img/tim/";
        $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowed)) {
            $new_name = time() . "_" . rand(1000, 9999) . "." . $ext;
            
            if(move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name)){
                // Hapus foto lama
                if (!empty($foto) && file_exists($target_dir . $foto)) {
                    unlink($target_dir . $foto);
                }
                $foto = $new_name;
            }
        }
    }

    $query = "UPDATE struktur_organisasi SET 
              nama='$nama', 
              jabatan='$jabatan', 
              email='$email', 
              pendidikan='$pendidikan', 
              urutan=$urutan, 
              foto='$foto' 
              WHERE id=$id";

    if (pg_query($conn, $query)) {
        header("Location: timAdmin.php?update=1");
        exit;
    } else {
        echo "Gagal update database!";
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

        <h2 class="fw-bold text-center mb-4">Edit Anggota Tim</h2>

        <div class="text-center">
            <?php 
                $imgSrc = !empty($data['foto']) ? "../assets/img/tim/".$data['foto'] : "../assets/img/default-user.png";
            ?>
            <img src="<?= $imgSrc ?>" class="img-preview" alt="Foto Profil">
        </div>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Nama Lengkap</label>
            <input type="text" name="nama" class="input" value="<?= htmlspecialchars($data['nama']) ?>" required>

            <label class="fw-bold">Jabatan</label>
            <input type="text" name="jabatan" class="input" value="<?= htmlspecialchars($data['jabatan']) ?>" required>

            <label class="fw-bold">Email Kontak</label>
            <input type="email" name="email" class="input" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>

            <label class="fw-bold">Pendidikan Terakhir</label>
            <input type="text" name="pendidikan" class="input" value="<?= htmlspecialchars($data['pendidikan'] ?? '') ?>" required>

            <label class="fw-bold">Urutan Tampil</label>
            <input type="number" name="urutan" class="input" value="<?= $data['urutan'] ?>">

            <label class="fw-bold">Ganti Foto (Opsional)</label>
            <input type="file" name="foto" class="input-file" accept="image/*">

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="update">
                    <span>Update</span>
                </button>

                <a href="timAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>

    </div>
</div>

</body>
</html>