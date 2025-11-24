<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

// Ambil data lama
$id = $_GET['id'];
$q = pg_query($conn, "SELECT * FROM berita WHERE id=$id");
$data = pg_fetch_assoc($q);

if (isset($_POST['submit'])) {
    $judul = pg_escape_string($conn, $_POST['judul']);
    $isi = pg_escape_string($conn, $_POST['isi']);
    $gambar_lama = $data['gambar'];

    $upload_dir = "../assets/uploads/berita/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $gambar = $gambar_lama;

    if (!empty($_FILES['gambar']['name'])) {

        $clean_name = preg_replace("/[^A-Za-z0-9.\-_]/", "_", $_FILES['gambar']['name']);
        $nama_file = time() . "_" . $clean_name;

        $path = $upload_dir . $nama_file;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $path)) {
            $gambar = $nama_file;

            if (!empty($gambar_lama) && file_exists($upload_dir . $gambar_lama)) {
                unlink($upload_dir . $gambar_lama);
            }
        }
    }

    pg_query($conn, "
        UPDATE berita SET 
            judul='$judul',
            isi='$isi',
            gambar='$gambar'
        WHERE id=$id
    ");

    header("Location: beritaAdmin.php?edit=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<style>
    .edit-card {
        max-width: 700px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 0 15px rgba(0,0,0,0.12);
        position: relative;
    }

    .edit-image-preview {
        width: 70%;
        border-radius: 14px;
        margin: 10px auto;
        display: block;
    }

    .filename-box {
        font-size: 14px;
        color: #333;
        background: #efefef;
        padding: 8px 12px;
        border-radius: 8px;
        margin-top: 6px;
        display: inline-block;
    }

    /* BUTTON BASE */
    .BtnBase {
        width: 150px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-weight: 600;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: .3s;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        z-index: 2;
    }

    /* UPDATE BUTTON (HITAM → PUTIH) */
    .BtnUpdate {
        background: black;
        color: white;
    }

    .BtnUpdate::before {
        content: "";
        position: absolute;
        width: 160%;
        height: 160%;
        background: white;
        top: 50%;
        left: -160%;
        transform: translateY(-50%);
        border-radius: 50%;
        transition: .35s ease;
        z-index: 0;
    }

    .BtnUpdate:hover::before {
        left: 0%;
        border-radius: 0;
    }

    .BtnUpdate:hover {
        color: black;
    }

    /* BACK BUTTON (PUTIH → HITAM) */
    .BtnBack {
        background: white;
        color: black;
        border: 2px solid black;
    }

    .BtnBack::before {
        content: "";
        position: absolute;
        width: 160%;
        height: 160%;
        background: black;
        top: 50%;
        left: 160%;
        transform: translateY(-50%);
        border-radius: 50%;
        transition: .35s ease;
        z-index: 0;
    }

    .BtnBack:hover::before {
        left: 0%;
        border-radius: 0;
    }

    .BtnBack:hover {
        color: white;
    }

    .BtnBase span {
        position: relative;
        z-index: 2;
    }

    /* BUTTON POSITION */
    .button-row {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
    }

    .BtnBase:active {
        transform: scale(0.94) translateY(2px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.18);
    }
</style>

<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Edit Berita</h2>

        <?php if ($data['gambar']) { ?>
            <img src="../assets/uploads/berita/<?= $data['gambar']; ?>" class="edit-image-preview">
            <div class="filename-box text-center">File: <?= $data['gambar']; ?></div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data" class="mt-4">

            <label class="fw-bold">Judul</label>
            <input type="text" name="judul" class="form-control" value="<?= $data['judul']; ?>" required>

            <label class="fw-bold mt-3">Isi Berita</label>
            <textarea name="isi" rows="7" class="form-control" required><?= $data['isi']; ?></textarea>

            <label class="fw-bold mt-3">Ubah Gambar</label>
            <input type="file" name="gambar" class="form-control mb-3">

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Update</span>
                </button>

                <a href="beritaAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>