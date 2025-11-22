<?php
include '../config/database.php';

$query = "SELECT * FROM berita ORDER BY tanggal_post DESC";
$result = pg_query($conn, $query);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<!-- Hero Section -->
<section class="hero-berita">
    <div class="text-center text-white">
        <h1 class="fw-bold">Berita Laboratorium</h1>
        <p class="lead mt-2">Semua berita dan informasi Lab-AI</p>
    </div>
</section>

<div class="container py-5">
    <h3 class="fw-bold text-primary mb-4">Semua Berita</h3>

    <div class="row g-4">

        <!-- ======================== -->
        <!-- 3 CARD DARI HOME (STATIC) -->
        <!-- ======================== -->

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <img src="../assets/img/berita1.jpg" class="card-img-top grayscale">
                <div class="card-body">
                    <small class="text-muted">12 November 2025</small>
                    <h5 class="card-title mt-2">Kegiatan Riset AI 2025</h5>
                    <p class="card-text">Laboratorium AI mengadakan pelatihan pemodelan machine learning untuk mahasiswa.</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Selengkapnya</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <img src="../assets/img/berita2.jpg" class="card-img-top grayscale">
                <div class="card-body">
                    <small class="text-muted">13 November 2025</small>
                    <h5 class="card-title">Workshop Data Science</h5>
                    <p class="card-text">Pelatihan intensif analisis data dan visualisasi menggunakan Python dan Tableau.</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Selengkapnya</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <img src="../assets/img/berita3.jpg" class="card-img-top grayscale">
                <div class="card-body">
                    <small class="text-muted">14 November 2025</small>
                    <h5 class="card-title">Kolaborasi Industri</h5>
                    <p class="card-text">Lab-AI menjalin kerja sama dengan startup lokal untuk proyek AI vision.</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Selengkapnya</a>
                </div>
            </div>
        </div>

        <!-- ======================== -->
        <!-- BERITA DARI DATABASE     -->
        <!-- ======================== -->
        <?php while ($b = pg_fetch_assoc($result)) { ?>
        <div class="col-md-4">
            <div class="news-card shadow-sm">

                <img 
                    src="../assets/uploads/berita/<?= $b['gambar'] ?: 'default.jpg'; ?>" 
                    class="w-100 news-img">

                <div class="p-3">
                    <small class="text-muted">
                        <?= date("F d, Y", strtotime($b['tanggal_post'])); ?>
                    </small>

                    <h5 class="mt-2 fw-bold"><?= $b['judul']; ?></h5>

                    <p class="text-muted" style="font-size:14px;">
                        <?= mb_strimwidth(strip_tags($b['isi']), 0, 120, "..."); ?>
                    </p>

                    <a href="detail_berita.php?id=<?= $b['id']; ?>" class="text-primary fw-bold">
                        Selengkapnya →
                    </a>
                </div>

            </div>
        </div>
        <?php } ?>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
