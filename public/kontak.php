<?php
include '../includes/header.php';
include '../includes/navbar.php';
include '../config/database.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $pesan = $_POST['pesan'] ?? '';

    if ($nama !== "" && $email !== "" && $pesan !== "") {
        $jenis = "pesan_kontak";
        $isi = "Nama: $nama | Email: $email | Pesan: $pesan";

        $query = "INSERT INTO kontak (jenis, isi) VALUES ($1, $2)";
        $result = pg_query_params($conn, $query, array($jenis, $isi));

        if ($result) {
            $success = "Pesan berhasil dikirim!";
        } else {
            $error = "Gagal menyimpan pesan.";
        }
    } else {
        $error = "Semua field wajib diisi.";
    }
}
?>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="contact-container">

    <h1 class="title">Kontak Kami</h1>

    <?php if ($success): ?>
        <div class="alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <div class="contact-wrapper">

        <!-- ================= FORM (KIRI) ================= -->
        <div class="form-section">
            <form method="POST">
                <div class="form-row-inline">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Pesan</label>
                        <input type="text" name="pesan" required>
                    </div>

                    <button class="kirim-btn" type="submit">Kirim</button>
                </div>
            </form>
        </div>

        <!-- ================= INFO (KANAN) ================= -->
        <div class="info-section">

            <h3 class="info-title">Alamat</h3>
            <p>Jl. Soekarno Hatta No. 9, Malang</p>

            <h3 class="info-title">Email</h3>
            <p>labteknologi@example.com</p>

            <h3 class="info-title">Telepon</h3>
            <p>0812-3456-7890</p>

            <h3 class="info-title">Lokasi</h3>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps?q=politeknik+negeri+malang&output=embed">
                </iframe>
            </div>

        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
