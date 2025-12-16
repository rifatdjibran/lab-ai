<?php
session_start();
require_once "../config/database.php";

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = intval($_GET['id']);

// Ambil data penelitian lama
$q = pg_query($conn, "SELECT * FROM penelitian WHERE id = $id");
$data = pg_fetch_assoc($q);

if (!$data) {
    echo "Data penelitian tidak ditemukan!";
    exit;
}

// PROSES UPDATE
if (isset($_POST['submit'])) {

    $judul      = pg_escape_string($conn, $_POST['judul']);
    $peneliti   = pg_escape_string($conn, $_POST['peneliti']);
    $tahun      = intval($_POST['tahun']);
    $deskripsi  = pg_escape_string($conn, $_POST['deskripsi']);

    $update = pg_query($conn, "
        UPDATE penelitian SET
            judul = '$judul',
            peneliti = '$peneliti',
            tahun = $tahun,
            deskripsi = '$deskripsi'
        WHERE id = $id
    ");

    if ($update) {
        header("Location: penelitianAdmin.php?update=1");
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
    <title>Edit Penelitian | Lab AI Admin</title>

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

/* PREVIEW GAMBAR */
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

/* ============================
   INPUT STYLE (UIVERSE)
============================= */
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

/* FILE INPUT CUSTOMIZED */
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

/* TEXTAREA FIX */
textarea.input {
    resize: vertical;
}

/* LABEL */
label.fw-bold {
    margin-bottom: 5px;
    margin-top: 18px;
    font-weight: 600;
}

/* ============================
   BUTTON STYLES
============================= */
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

        <h2 class="fw-bold text-center mb-4">Edit Penelitian</h2>

        <form method="POST">

            <label class="fw-bold">Judul Penelitian</label>
            <input type="text" name="judul" class="input" value="<?= $data['judul'] ?>" required>

            <label class="fw-bold">Peneliti</label>
            <input type="text" name="peneliti" class="input" value="<?= $data['peneliti'] ?>" required>

            <label class="fw-bold">Tahun</label>
            <input type="number" name="tahun" class="input" value="<?= $data['tahun'] ?>" required>

            <label class="fw-bold">Deskripsi</label>
            <textarea name="deskripsi" rows="6" class="input" required><?= $data['deskripsi'] ?></textarea>

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Update</span>
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