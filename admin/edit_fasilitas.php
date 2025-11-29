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
.input,
textarea.input {
    width: 100%;
    padding: 0.875rem;
    font-size: 1rem;
    border: 1.5px solid #000;
    border-radius: 0.5rem;
    box-shadow: 2.5px 3px 0 #000;
    outline: none;
    transition: ease 0.25s;
    background: white;
}
.input:focus,
textarea.input:focus {
    box-shadow: 5.5px 7px 0 black;
}
.input-file {
    width: 100%;
    padding: 0.7rem;
    border-radius: 0.5rem;
    border: 1.5px solid #000;
    background: #fafafa;
    box-shadow: 2.5px 3px 0 #000;
    transition: .25s ease;
}
.input-file:focus {
    box-shadow: 5.5px 7px 0 black;
}
textarea.input {
    resize: vertical;
}
label.fw-bold {
    margin-bottom: 5px;
    margin-top: 18px;
    font-weight: 600;
}

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
    z-index: 2;
    position: relative;
}
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

<?php include "../includes/footer.php"; ?>
</body>
</html>
