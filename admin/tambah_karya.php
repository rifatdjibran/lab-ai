<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

$pesan = '';

if (isset($_POST['submit'])) {
    // Pastikan menggunakan prepared statement untuk keamanan yang lebih baik,
    // tapi karena Anda menggunakan pg_escape_string, kita pertahankan gayanya.

    $id_anggota   = intval($_POST['id_anggota']);
    $jenis_karya  = pg_escape_string($conn, $_POST['jenis_karya']);
    $judul        = pg_escape_string($conn, $_POST['judul']);
    $tahun        = intval($_POST['tahun']);
    $deskripsi    = pg_escape_string($conn, $_POST['deskripsi'] ?? ''); // Deskripsi bisa kosong
    $admin_id     = $_SESSION['admin_id'];

    // Nomor dokumen hanya relevan untuk HAKI
    $nomor_dokumen = ($jenis_karya === 'HAKI') ? pg_escape_string($conn, $_POST['nomor_dokumen']) : NULL;

    // Query Insert ke database       
    $insert = pg_query($conn, "
        INSERT INTO karya_dosen (id_anggota, jenis_karya, judul, tahun, nomor_dokumen, deskripsi, created_at)
        VALUES ($id_anggota, '$jenis_karya', '$judul', $tahun, 
                " . ($nomor_dokumen ? "'$nomor_dokumen'" : "NULL") . ", 
                '$deskripsi', NOW())
    ");

    if ($insert) {
        header("Location: karyaAdmin.php?add=1");
        exit;
    } else {
        $error = pg_last_error($conn);
        $pesan = "<div class='alert alert-danger'>Gagal menambahkan data: " . htmlspecialchars($error) . "</div>";
    }
}

// Ambil daftar anggota lab untuk dropdown
$anggota_list = pg_query($conn, "SELECT id, nama FROM struktur_organisasi ORDER BY nama ASC");
$jenis_karya_options = ['Riset', 'HAKI', 'PPM', 'Aktivitas'];

// Gaya CSS yang Anda kirimkan di edit_publikasi.php harus di-include/copy-paste di sini juga
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karya Dosen | Lab AI Admin</title>

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

        <h2 class="fw-bold text-center mb-4">Tambah Karya Dosen</h2>
        <?= $pesan ?>

        <form method="POST">

            <label class="fw-bold">Anggota Lab</label>
            <select name="id_anggota" class="input" required>
                <option value="">-- Pilih Anggota --</option>
                <?php while ($row = pg_fetch_assoc($anggota_list)): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
                <?php endwhile; ?>
            </select>

            <label class="fw-bold mt-3">Jenis Karya</label>
            <select name="jenis_karya" class="input" required onchange="toggleNomorDokumen(this.value)">
                <option value="">-- Pilih Jenis --</option>
                <?php foreach ($jenis_karya_options as $jenis): ?>
                    <option value="<?= $jenis ?>"><?= $jenis ?></option>
                <?php endforeach; ?>
            </select>

            <label class="fw-bold mt-3">Judul</label>
            <input type="text" name="judul" class="input" required>

            <label class="fw-bold mt-3">Tahun</label>
            <input type="number" name="tahun" class="input" required>

            <div id="nomor_dokumen_group" style="display: none;">
                <label class="fw-bold mt-3">Nomor Permohonan (Khusus HAKI)</label>
                <input type="text" name="nomor_dokumen" class="input">
            </div>

            <label class="fw-bold mt-3">Deskripsi (Opsional)</label>
            <textarea name="deskripsi" class="input"></textarea>

            <div class="button-row">
                <button class="BtnBase BtnUpdate" name="submit">
                    <span>Simpan</span>
                </button>

                <a href="karyaAdmin.php" class="BtnBase BtnBack text-decoration-none">
                    <span>Kembali</span>
                </a>
            </div>

        </form>

    </div>
</div>

<script>
    // Fungsi JavaScript untuk menampilkan kolom Nomor Dokumen hanya untuk HAKI
    function toggleNomorDokumen(jenis) {
        const docGroup = document.getElementById('nomor_dokumen_group');
        const docInput = document.getElementsByName('nomor_dokumen')[0]; // Ambil inputnya
        
        if (jenis === 'HAKI') {
            docGroup.style.display = 'block';
            docInput.setAttribute('required', 'required'); 
        } else {
            docGroup.style.display = 'none';
            docInput.removeAttribute('required');
            docInput.value = ''; // Kosongkan nilai
        }
    }
</script>



</body>
</html>