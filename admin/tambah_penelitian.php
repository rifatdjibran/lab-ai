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

    $judul = pg_escape_string($conn, $_POST['judul']);
    $peneliti = pg_escape_string($conn, $_POST['peneliti']);
    $tahun = pg_escape_string($conn, $_POST['tahun']);
    $desk = pg_escape_string($conn, $_POST['deskripsi']);
    $admin_id = $_SESSION['admin_id'];

    // Folder upload
    $upload_dir = "../assets/uploads/penelitian/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    // Upload file penelitian (PDF)
    $file_laporan = null;

    if (!empty($_FILES['file_laporan']['name'])) {
        $clean = preg_replace("/[^A-Za-z0-9.\-_]/", "_", $_FILES['file_laporan']['name']);
        $file = time() . "_" . $clean;

        if (move_uploaded_file($_FILES['file_laporan']['tmp_name'], $upload_dir . $file)) {
            $file_laporan = $file;
        }
    }

    pg_query($conn, "
        INSERT INTO penelitian(judul, peneliti, tahun, deskripsi, file_laporan, created_at, admin_id)
        VALUES ('$judul', '$peneliti', '$tahun', '$desk', '$file_laporan', NOW(), $admin_id)
    ");

    header("Location: penelitianAdmin.php?add=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penelitian | Lab AI Admin</title>

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

.input,
textarea.input {
    width: 100%;
    padding: 0.875rem;
    font-size: 1rem;
    border: 1.5px solid #000;
    border-radius: 0.5rem;
    box-shadow: 2.5px 3px 0 #000;
    transition: 0.25s ease;
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
}

label.fw-bold {
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
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.BtnUpdate {
    background: black;
    color: white;
}

.BtnBack {
    background: white;
    color: black;
    border: 2px solid black;
}

.button-row {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}
</style>

<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Tambah Penelitian</h2>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Judul Penelitian</label>
            <input type="text" name="judul" class="input" required>

            <label class="fw-bold mt-3">Peneliti</label>
            <input type="text" name="peneliti" class="input" required>

            <label class="fw-bold mt-3">Tahun</label>
            <input type="number" name="tahun" class="input" min="1900" max="2100" required>

            <label class="fw-bold mt-3">Deskripsi</label>
            <textarea name="deskripsi" rows="6" class="input" required></textarea>

            <label class="fw-bold mt-3">File Laporan (PDF)</label>
            <input type="file" name="file_laporan" class="input-file" accept=".pdf" required>

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Simpan</span>
                </button>

                <a href="penelitianAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>

    </div>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>
