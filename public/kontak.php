<?php
include '../includes/header.php';
include '../includes/navbar.php';
include '../config/database.php'; // Pastikan $conn terdefinisi

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$nama   = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$email  = isset($_POST['email']) ? trim($_POST['email']) : '';
$subjek = isset($_POST['subjek']) ? trim($_POST['subjek']) : '';
$pesan  = isset($_POST['pesan']) ? trim($_POST['pesan']) : '';


    if ($nama && $email && $subjek && $pesan) {
        // INSERT ke database
        $q = pg_query_params(
            $conn,
            "INSERT INTO kontak (nama, email, subjek, pesan, tanggal, status) 
             VALUES ($1, $2, $3, $4, now(), $5)",
            [$nama, $email, $subjek, $pesan, 'baru']
        );

        if ($q) {
            $success = "Pesan berhasil dikirim!";
        } else {
            $error = "Gagal mengirim pesan!";
        }
    } else {
        $error = "Semua kolom harus diisi.";
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
                            <label class="form-label">Subjek</label>
                            <input type="text" name="subjek" class="form-control" required>
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
