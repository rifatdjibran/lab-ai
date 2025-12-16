<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

// Ambil data lama
$id = $_GET['id'];
$q = pg_query($conn, "SELECT * FROM fasilitas WHERE id=$id");
$data = pg_fetch_assoc($q);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

if (isset($_POST['submit'])) {
    $nama = pg_escape_string($conn, $_POST['nama_fasilitas']);
    $desk = pg_escape_string($conn, $_POST['deskripsi']);

    $gambar_lama = $data['gambar'];
    $upload_dir = "../assets/uploads/fasilitas/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $gambar = $gambar_lama;

    // Upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {

        $clean_name = preg_replace("/[^A-Za-z0-9.\-_]/", "_", $_FILES['gambar']['name']);
        $nama_file = time() . "_" . $clean_name;

        $path = $upload_dir . $nama_file;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $path)) {
            $gambar = $nama_file;

            // Hapus gambar lama
            if (!empty($gambar_lama) && file_exists($upload_dir . $gambar_lama)) {
                unlink($upload_dir . $gambar_lama);
            }
        }
    }

    pg_query($conn, "
        UPDATE fasilitas SET 
            nama_fasilitas='$nama',
            deskripsi='$desk',
            gambar='$gambar'
        WHERE id=$id
    ");

    header("Location: fasilitasAdmin.php?edit=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fasilitas | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>


<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Edit Fasilitas</h2>

        <?php if ($data['gambar']) { ?>
            <img src="../assets/uploads/fasilitas/<?= $data['gambar']; ?>" class="edit-image-preview">
            <div class="filename-box text-center">File: <?= $data['gambar']; ?></div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data" class="mt-4">

            <label class="fw-bold">Nama Fasilitas</label>
            <input type="text" name="nama_fasilitas" class="input" value="<?= $data['nama_fasilitas']; ?>" required>

            <label class="fw-bold mt-3">Deskripsi</label>
            <textarea name="deskripsi" rows="7" class="input" required><?= $data['deskripsi']; ?></textarea>

            <label class="fw-bold mt-3">Ubah Gambar</label>
            <input type="file" name="gambar" class="input-file">

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Update</span>
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
