<?php
include '../config/database.php';

// Query berita
$query = "SELECT * FROM berita ORDER BY tanggal DESC";
$result = pg_query($conn, $query);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="hero-berita">
    <div class="text-center text-white">
        <h1 class="fw-bold">Berita Laboratorium</h1>
        <p class="lead mt-2">Ikuti kegiatan terbaru dan informasi penting dari Lab AI</p>
    </div>
</section>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Latest News</h3>
        <a href="#" class="text-danger">See all</a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="news-card shadow-sm">
                <img src="<?= $b['gambar']; ?>" class="news-img w-100" alt="gambar">

                <div class="p-3">
                    <small class="text-muted">
                        <?= date("F d, Y", strtotime($b['tanggal'])); ?> • <?= $b['penulis']; ?>
                    </small>

                    <h5 class="mt-2 fw-bold"><?= $b['judul']; ?></h5>

                    <p class="text-muted" style="font-size:14px;">
                        <?= mb_strimwidth(strip_tags($b['isi']), 0, 110, "..."); ?>
                    </p>

                    <a href="detail_berita.php?id=<?= $b['id']; ?>" class="text-primary fw-bold">
                        Read more →
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
