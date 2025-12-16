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
    $kategori = pg_escape_string($conn, $_POST['kategori']);
    $link_publikasi = pg_escape_string($conn, $_POST['link_publikasi']);

    $id_anggota_utama = !empty($_POST['id_anggota_utama']) ? intval($_POST['id_anggota_utama']) : 'NULL';

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

    
    $update = pg_query($conn, "
    UPDATE publikasi SET 
        judul = '$judul',
        penulis = '$penulis',
        jurnal = '$jurnal',
        tahun = $tahun,
        kategori = '$kategori',
        link_publikasi = '$link_publikasi',
        file_publikasi = '$file_baru',
        id_anggota_utama = $id_anggota_utama -- <<< KOLOM BARU DI SINI
    WHERE id = $id
    ");

    if ($update) {
        header("Location: publikasiAdmin.php?update=1");
        exit;
    } else {
        echo "Gagal mengupdate data! Error: " . pg_last_error($conn);
    }
}

// Query untuk mengambil daftar anggota lab (untuk dropdown)
$anggota_list = pg_query($conn, "SELECT id, nama FROM struktur_organisasi ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Publikasi | Lab AI Admin</title>

    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

<?php include "../includes/header_admin.php"; ?>




<div class="container py-5">
    <div class="edit-card">

        <h2 class="fw-bold text-center mb-4">Edit Publikasi</h2>

        <form method="POST" enctype="multipart/form-data">

            <label class="fw-bold">Anggota Utama Lab (Opsional)</label>
            <select name="id_anggota_utama" class="input">
                <option value="">-- Pilih Anggota Lab (Jika ada) --</option>
                <?php while ($row = pg_fetch_assoc($anggota_list)): 
                    // Tentukan opsi mana yang dipilih
                    $selected = ($row['id'] == $data['id_anggota_utama']) ? 'selected' : '';
                ?>
                    <option value="<?= $row['id'] ?>" <?= $selected ?>><?= htmlspecialchars($row['nama']) ?></option>
                <?php endwhile; ?>
            </select>
            <small class="text-muted d-block mt-1">*Digunakan untuk menampilkan di Profil Dosen.</small>

            <label class="fw-bold">Judul Publikasi</label>
            <input type="text" name="judul" class="input" value="<?= $data['judul'] ?>" required>

            <label class="fw-bold">Penulis</label>
            <input type="text" name="penulis" class="input" value="<?= $data['penulis'] ?>" required>

            <label class="fw-bold">Nama Jurnal</label>
            <input type="text" name="jurnal" class="input" value="<?= $data['jurnal'] ?>" required>

            <label class="fw-bold">Tahun Terbit</label>
            <input type="number" name="tahun" class="input" value="<?= $data['tahun'] ?>" required>

            <label class="fw-bold mt-3">Kategori</label>
            <select name="kategori" class="input" required>
                <option value="Jurnal Ilmiah"        <?= ($data['kategori']=="Jurnal Ilmiah") ? "selected" : "" ?>>Jurnal Ilmiah</option>
                <option value="Prosiding Konferensi" <?= ($data['kategori']=="Prosiding Konferensi") ? "selected" : "" ?>>Prosiding Konferensi</option>
                <option value="HKI"                  <?= ($data['kategori']=="HKI") ? "selected" : "" ?>>HKI</option>
                <option value="Buku"                 <?= ($data['kategori']=="Buku") ? "selected" : "" ?>>Buku</option>
                <option value="Modul Ajar"           <?= ($data['kategori']=="Modul Ajar") ? "selected" : "" ?>>Modul Ajar</option>
                <option value="DLL"                  <?= ($data['kategori']=="DLL") ? "selected" : "" ?>>DLL</option>
            </select>


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

</body>
</html>
