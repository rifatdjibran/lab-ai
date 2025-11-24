<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Ambil semua berita (terbaru → lama)
$query = "SELECT * FROM berita ORDER BY tanggal DESC";
$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$beritaList = pg_fetch_all($result);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Berita Laboratorium</h1>
        <p class="lead mt-2">Ikuti kegiatan terbaru dan informasi penting dari Lab AI</p>
    </div>
</section>

<div class="container py-5">

    <h3 class="fw-bold text-primary mb-4">Berita Lengkap</h3>

    <!-- ============================= -->
    <!-- PART 1: Tampilkan 6 berita pertama (FULL) -->
    <!-- ============================= -->
    <?php if ($beritaList): ?>
        <?php 
        $counter = 0;
        foreach ($beritaList as $b):
            if ($counter < 6): 
        ?>
            <div class="mb-5 pb-4 border-bottom">

                <!-- Gambar Besar -->
                <img src="../assets/uploads/berita/<?= $b['gambar'] ?: 'default.jpg'; ?>" 
                     class="img-fluid rounded mb-3" 
                     style="max-height:360px; object-fit:cover; width:100%;">

                <!-- Info -->
                <small class="text-muted">
                    <?= date("F d, Y", strtotime($b['tanggal'])); ?> • Penulis ID: <?= $b['penulis']; ?>
                </small>

                <!-- Judul -->
                <h3 class="fw-bold mt-2"><?= $b['judul']; ?></h3>

                <!-- Isi berita (full) -->
                <p style="font-size:16px;"><?= nl2br($b['isi']); ?></p>

                <a href="detail_berita.php?id=<?= $b['id'] ?>" class="btn btn-primary mt-2">
                    Baca Selengkapnya →
                </a>
            </div>

        <?php 
            endif; 
            $counter++;
        endforeach; 
        ?>
    <?php endif; ?>



    <!-- ============================= -->
    <!-- PART 2: Berita lainnya (Card) -->
    <!-- ============================= -->

    <div class="row g-4">

        <?php foreach ($beritaList as $index => $b): ?>
            <?php if ($index >= 6): ?> <!-- mulai card dari berita ke-7 -->
                <div class="col-md-4">
                    <div class="news-card shadow-sm">

                        <img 
                            src="../assets/uploads/berita/<?= $b['gambar'] ?: 'default.jpg'; ?>" 
                            class="news-img w-100"
                            alt="gambar berita">

                        <div class="p-3">
                            <small class="text-muted">
                                <?= date("F d, Y", strtotime($b['tanggal_post'])); ?>
                                • Penulis ID: <?= $b['penulis']; ?>
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
            <?php endif; ?>
        <?php endforeach; ?>

    </div>

</div>

<?php include '../includes/footer.php'; ?>
