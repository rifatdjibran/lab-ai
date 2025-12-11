<?php
include '../includes/header.php';
include '../includes/navbar.php';
include '../config/database.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi input
    $nama   = isset($_POST['nama']) ? trim(htmlspecialchars($_POST['nama'])) : '';
    $email  = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
    $subjek = isset($_POST['subjek']) ? trim($_POST['subjek']) : '';
    $pesan  = isset($_POST['pesan']) ? trim(htmlspecialchars($_POST['pesan'])) : '';

    if ($nama && $email && $subjek && $pesan) {
        // INSERT ke database
        $q = pg_query_params(
            $conn,
            "INSERT INTO kontak (nama, email, subjek, pesan, tanggal, status) 
             VALUES ($1, $2, $3, $4, now(), $5)",
            [$nama, $email, $subjek, $pesan, 'baru']
        );

        if ($q) {
            $success = "Terima kasih! Pesan Anda telah kami terima.";
            // Reset form agar tidak submit ulang saat refresh
            $nama = $email = $subjek = $pesan = ""; 
        } else {
            $error = "Maaf, terjadi kesalahan sistem. Silakan coba lagi.";
        }
    } else {
        $error = "Mohon lengkapi semua kolom isian.";
    }
}
?>

<style>
    /* 1. Hero Section Konsisten */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 60px 0;
        margin-bottom: 3rem;
        color: white;
    }

    /* 2. Wrapper Kontak Unik */
    .contact-wrapper {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08); /* Bayangan lembut */
        overflow: hidden;
        margin-bottom: 50px;
    }

    /* 3. Bagian Kiri (Info Panel) */
    .contact-info-panel {
        background: #0d6efd;
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }
    
    /* Hiasan lingkaran transparan di background */
    .contact-info-panel::before {
        content: '';
        position: absolute;
        top: -50px;
        left: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .contact-info-panel::after {
        content: '';
        position: absolute;
        bottom: -30px;
        right: -30px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .info-item {
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }
    .info-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
        flex-shrink: 0;
    }

    /* 4. Bagian Kanan (Form) */
    .contact-form-panel {
        padding: 3rem;
    }

    /* Floating Label Style */
    .form-floating > .form-control,
    .form-floating > .form-select {
        border: 2px solid #f1f3f5;
        border-radius: 10px;
        background-color: #fcfcfc;
    }
    
    .form-floating > .form-control:focus,
    .form-floating > .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        background-color: #fff;
    }

    /* Tombol Kirim */
    .btn-send {
        background: #0d6efd;
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    .btn-send:hover {
        background: #0b5ed7;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .contact-info-panel {
            padding: 2rem;
            text-align: center;
        }
        .info-item {
            justify-content: center;
            text-align: left; /* Agar teks jam tetap rapi */
        }
        .contact-form-panel {
            padding: 2rem;
        }
    }
</style>

<section class="hero-section text-center">
    <div class="container">
        <h1 class="fw-bold display-5">Hubungi Kami</h1>
        <p class="lead opacity-75 mt-2">Sampaikan keluhan, saran, atau kesan Anda untuk kemajuan Lab AI.</p>
    </div>
</section>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="contact-wrapper">
                <div class="row g-0">
                    
                    <div class="col-md-5 contact-info-panel">
                        <h3 class="fw-bold mb-4 position-relative" style="z-index: 2;">Kontak Informasi</h3>
                        <p class="mb-5 position-relative" style="z-index: 2; opacity: 0.9;">
                            Jangan ragu untuk menghubungi kami. Tim kami akan segera merespons pesan Anda.
                        </p>

                        <div class="info-item position-relative" style="z-index: 2;">
                            <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
                            <div>
                                <small class="d-block opacity-75">Lokasi</small>
                                <span class="fw-medium">Lantai 2 Gedung Pascasarjana</span>
                            </div>
                        </div>

                        <div class="info-item position-relative" style="z-index: 2;">
                            <div class="info-icon"><i class="bi bi-envelope"></i></div>
                            <div>
                                <small class="d-block opacity-75">Email</small>
                                <span class="fw-medium">lab.ai@polinema.ac.id</span>
                            </div>
                        </div>

                        <div class="info-item position-relative" style="z-index: 2;">
                            <div class="info-icon"><i class="bi bi-clock"></i></div>
                            <div>
                                <small class="d-block opacity-75">Jam Operasional</small>
                                <span class="fw-bold fs-5">08.00 - 16.00</span>
                                <div class="small opacity-75">Senin - Jumat</div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-7 contact-form-panel">
                        <h4 class="fw-bold text-primary mb-4">Kirim Pesan</h4>

                        <?php if ($success): ?>
                            <div class="alert alert-success d-flex align-items-center rounded-3 shadow-sm border-0 bg-success-subtle text-success">
                                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                <div><?= $success ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center rounded-3 shadow-sm border-0 bg-danger-subtle text-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div><?= $error ?></div>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingNama" name="nama" placeholder="Nama Lengkap" required value="<?= isset($nama) ? $nama : '' ?>">
                                <label for="floatingNama">Nama Lengkap</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="name@example.com" required value="<?= isset($email) ? $email : '' ?>">
                                <label for="floatingEmail">Alamat Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="floatingSelect" name="subjek" required>
                                    <option value="" selected disabled>Pilih Kategori Pesan</option>
                                    <option value="Keluhan Penggunaan Lab" <?= (isset($subjek) && $subjek == 'Keluhan Penggunaan Lab') ? 'selected' : '' ?>>Keluhan Penggunaan Lab</option>
                                    <option value="Saran & Masukan" <?= (isset($subjek) && $subjek == 'Saran & Masukan') ? 'selected' : '' ?>>Saran & Masukan</option>
                                    <option value="Kesan & Apresiasi" <?= (isset($subjek) && $subjek == 'Kesan & Apresiasi') ? 'selected' : '' ?>>Kesan & Apresiasi</option>
                                    <option value="Pertanyaan Umum" <?= (isset($subjek) && $subjek == 'Pertanyaan Umum') ? 'selected' : '' ?>>Pertanyaan Umum</option>
                                    <option value="Lainnya" <?= (isset($subjek) && $subjek == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                                <label for="floatingSelect">Subjek Pesan</label>
                            </div>

                            <div class="form-floating mb-4">
                                <textarea class="form-control" placeholder="Tulis pesan di sini" id="floatingTextarea" name="pesan" style="height: 150px" required><?= isset($pesan) ? $pesan : '' ?></textarea>
                                <label for="floatingTextarea">Isi Pesan Anda</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn-send">
                                    <i class="bi bi-send-fill me-2"></i> Kirim Sekarang
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>