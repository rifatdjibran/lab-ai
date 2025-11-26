<?php
session_start();
require_once "../config/database.php";

// Ambil data lama
$id = $_GET['id'];
$q = pg_query($conn, "SELECT * FROM penelitian WHERE id = $id");
$data = pg_fetch_assoc($q);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {

    $judul      = pg_escape_string($conn, $_POST['judul']);
    $peneliti   = pg_escape_string($conn, $_POST['peneliti']);
    $tahun      = intval($_POST['tahun']);
    $deskripsi  = pg_escape_string($conn, $_POST['deskripsi']);

    $file_lama = $data['file_laporan'];
    $file_baru = $file_lama;

    // Cek apakah ada upload file baru
    if (!empty($_FILES['file_laporan']['name'])) {

        $namaFile = basename($_FILES['file_laporan']['name']);
        $targetDir = "../uploads/penelitian/";
        $targetFile = $targetDir . $namaFile;

        // Pindahkan file
        if (move_uploaded_file($_FILES['file_laporan']['tmp_name'], $targetFile)) {
            $file_baru = $namaFile; // ganti nama file baru

            // Hapus file lama jika ada
            if (!empty($file_lama) && file_exists($targetDir . $file_lama)) {
                unlink($targetDir . $file_lama);
            }
        }
    }

    // Update data
    $update = pg_query($conn, "
        UPDATE penelitian SET 
            judul = '$judul',
            peneliti = '$peneliti',
            tahun = $tahun,
            deskripsi = '$deskripsi',
            file_laporan = '$file_baru'
        WHERE id = $id
    ");

    if ($update) {
        header("Location: penelitian.php?update=success");
        exit;
    } else {
        echo "Gagal mengupdate data!";
    }
}

include "../includes/header.php";
include "../includes/navbar.php";
?>

<div class="container mt-4">
    <h3>Edit Penelitian</h3>

    <form action="" method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" value="<?= $data['judul'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Peneliti</label>
            <input type="text" name="peneliti" class="form-control" value="<?= $data['peneliti'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" value="<?= $data['tahun'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required><?= $data['deskripsi'] ?></textarea>
        </div>

        <div class="mb-3">
            <label>File Laporan (PDF)</label><br>

            <?php if (!empty($data['file_laporan'])): ?>
                <p>File saat ini: <strong><?= $data['file_laporan'] ?></strong></p>
            <?php endif; ?>

            <input type="file" name="file_laporan" class="form-control">
            <small>Kosongkan jika tidak ingin mengganti file.</small>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Update</button>
        <a href="penelitianAdmin.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include "../includes/footer.php"; ?>