<?php
session_start();
require_once "../config/database.php";

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = intval($_GET['id']);

// Ambil data publikasi lama
$q = pg_query($conn, "SELECT * FROM publikasi WHERE id = $id");
$data = pg_fetch_assoc($q);

if (!$data) {
    echo "Data publikasi tidak ditemukan!";
    exit;
}

// Proses Update
if (isset($_POST['submit'])) {

    $judul  = pg_escape_string($conn, $_POST['judul']);
    $penulis = pg_escape_string($conn, $_POST['penulis']);
    $jurnal = pg_escape_string($conn, $_POST['jurnal']);
    $tahun  = intval($_POST['tahun']);
    $link_publikasi = pg_escape_string($conn, $_POST['link_publikasi']);

    $file_lama = $data['file_publikasi'];
    $file_baru = $file_lama;

    $upload_dir = "../assets/uploads/publikasi/";

    // Jika ada file baru diupload
    if (!empty($_FILES['file_publikasi']['name'])) {

        $clean = preg_replace("/[^A-Za-z0-9.\-_]/", "_", $_FILES['file_publikasi']['name']);
        $namaFile = time() . "_" . $clean;

        if (move_uploaded_file($_FILES['file_publikasi']['tmp_name'], $upload_dir . $namaFile)) {
            $file_baru = $namaFile;

            // Hapus file lama jika ada dan tidak kosong
            if (!empty($file_lama) && file_exists($upload_dir . $file_lama)) {
                unlink($upload_dir . $file_lama);
            }
        }
    }

    // Query Update
    $update = pg_query($conn, "
        UPDATE publikasi SET 
            judul = '$judul',
            penulis = '$penulis',
            jurnal = '$jurnal',
            tahun = $tahun,
            link_publikasi = '$link_publikasi',
            file_publikasi = '$file_baru'
        WHERE id = $id
    ");

    if ($update) {
        header("Location: publikasiAdmin.php?update=1");
        exit;
    } else {
        echo "Gagal mengupdate data!";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Publikasi | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<style>
/* CARD WRAPPER */
.edit-card {
    max-width: 700px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 0 15px rgba(0,0,0,0.12);
}

/* INPUT STYLE */
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

label.fw-bold {
    margin-top: 18px;
    font-weight: 600;
}

/* BUTTON */
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
    transition: .35s ease;
}
.BtnUpdate:hover::before {
    left: 0%;
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
    transition: .35s ease;
}
.BtnBack:hover::before {
    left: 0%;
}
.BtnBack:hover {
    color: white;
}

.button-row {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}
</style>

<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Edit Publikasi</h2>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Judul Publikasi</label>
            <input type="text" name="judul" class="input" value="<?= $data['judul'] ?>" required>

            <label class="fw-bold">Penulis</label>
            <input type="text" name="penulis" class="input" value="<?= $data['penulis'] ?>" required>

            <label class="fw-bold">Nama Jurnal</label>
            <input type="text" name="jurnal" class="input" value="<?= $data['jurnal'] ?>" required>

            <label class="fw-bold">Tahun Terbit</label>
            <input type="number" name="tahun" class="input" value="<?= $data['tahun'] ?>" required>

            <label class="fw-bold">Link Publikasi</label>
            <input type="text" name="link_publikasi" class="input" value="<?= $data['link_publikasi'] ?>">

            <label class="fw-bold">File Publikasi</label>
            
            <?php if (!empty($data['file_publikasi'])): ?>
                <div class="filename-box mb-2">
                    File saat ini: <strong><?= $data['file_publikasi'] ?></strong>
                </div>
            <?php endif; ?>

            <input type="file" name="file_publikasi" class="input-file">
            <small style="color:#555;">Kosongkan jika tidak ingin mengganti file.</small>

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Update</span>
                </button>

                <a href="publikasiAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>

    </div>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>
