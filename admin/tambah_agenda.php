<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

require_once "../config/database.php";

if (isset($_POST['submit'])) {

    $nama = pg_escape_string($conn, $_POST['nama_kegiatan']);
    $desk = pg_escape_string($conn, $_POST['deskripsi']);
    $mulai = $_POST['tanggal_mulai'];
    $selesai = $_POST['tanggal_selesai'];
    $lokasi = pg_escape_string($conn, $_POST['lokasi']);

    $admin_id = $_SESSION['admin_id'];

    $upload_dir = "../assets/uploads/kegiatan/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $gambar = null;

    if (!empty($_FILES['gambar']['name'])) {
        $clean = preg_replace("/[^A-Za-z0-9.\-_]/", "_", $_FILES['gambar']['name']);
        $file = time() . "_" . $clean;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $file)) {
            $gambar = $file;
        }
    }

    pg_query($conn, "
        INSERT INTO kegiatan(nama_kegiatan,deskripsi,tanggal_mulai,tanggal_selesai,lokasi,gambar,created_at,admin_id)
        VALUES ('$nama','$desk','$mulai','$selesai','$lokasi','$gambar',NOW(),$admin_id)
    ");

    header("Location: agendaAdmin.php?add=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Agenda | Lab AI Admin</title>

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
        position: relative;
        z-index: 2;
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

        <h2 class="fw-bold text-center mb-4">Tambah Agenda</h2>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" class="form-control" required>

            <label class="fw-bold mt-3">Deskripsi</label>
            <textarea name="deskripsi" rows="6" class="form-control" required></textarea>

            <label class="fw-bold mt-3">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" required>

            <label class="fw-bold mt-3">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" required>

            <label class="fw-bold mt-3">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" required>

            <label class="fw-bold mt-3">Gambar</label>
            <input type="file" name="gambar" class="form-control">

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Simpan</span>
                </button>

                <a href="agendaAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>

    </div>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>
