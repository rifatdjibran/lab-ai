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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>


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