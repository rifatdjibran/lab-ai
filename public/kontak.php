<?php
include '../includes/header.php';
include '../includes/navbar.php';
include '../config/database.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];

    // Simpan 3 baris karena tabelnya jenis & isi
    $q1 = pg_query($koneksi, "INSERT INTO kontak (jenis, isi) VALUES ('nama', '$nama')");
    $q2 = pg_query($koneksi, "INSERT INTO kontak (jenis, isi) VALUES ('email', '$email')");
    $q3 = pg_query($koneksi, "INSERT INTO kontak (jenis, isi) VALUES ('pesan', '$pesan')");

    if ($q1 && $q2 && $q3) {
        $success = "Pesan berhasil dikirim!";
    } else {
        $error = "Gagal mengirim pesan!";
    }
}
?>

<link rel="stylesheet" href="style-kontak.css">

<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Hubungi Kami</h1>
        <p class="lead mt-2">Kami siap menerima pertanyaan, saran, dan masukan Anda</p>
    </div>
</section>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow contact-card">
                <div class="card-body p-4">

                    <h3 class="text-center mb-3 fw-bold">Hubungi Kami</h3>
                    <p class="text-center text-muted mb-4">
                        Silakan kirim pesan melalui form berikut
                    </p>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pesan</label>
                            <textarea name="pesan" rows="5" class="form-control" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Kirim Pesan
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
