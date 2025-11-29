<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Ambil berita terbaru
$query = "SELECT id, judul, isi, gambar, tanggal, penulis 
          FROM berita 
          ORDER BY tanggal DESC";

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
        <p class="lead mt-2">Ikuti informasi dan update terbaru dari Laboratorium</p>
    </div>
</section>

<!-- Berita Section -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Berita Terbaru</h2>

        <div class="row g-4">

            <?php if ($beritaList): ?>
                <?php foreach ($beritaList as $b): ?>
                    <div class="col-md-4">
                        <div class="card agenda-card shadow-sm">

                            <!-- Gambar -->
                            <img 
                                src="../assets/uploads/berita/<?= htmlspecialchars($b['gambar']) ?>" 
                                class="card-img-top"
                                style="height: 250px; object-fit: cover;"
                                alt="<?= htmlspecialchars($b['judul']) ?>">

                            <div class="card-body">

                                <!-- Judul -->
                                <h5 class="card-title fw-bold">
                                    <?= htmlspecialchars($b['judul']) ?>
                                </h5>

                                <!-- Deskripsi pendek -->
                                <p class="card-text">
                                    <?= mb_strimwidth(strip_tags($b['isi']), 0, 120, "..."); ?>
                                </p>

                                <!-- Tanggal -->
                                <p class="mb-1">
                                    <strong>Tanggal:</strong>
                                    <?= date('d F Y', strtotime($b['tanggal'])) ?>
                                </p>

                                <!-- Penulis -->
                                <p class="mb-1">
                                    <strong>Penulis:</strong>
                                    <?= htmlspecialchars($b['penulis']) ?>
                                </p>

                                <span class="badge bg-primary badge-status">Berita</span>

                                <div class="mt-3">
                                    <a href="detail_berita.php?id=<?= $b['id'] ?>" 
                                       class="btn btn-primary btn-sm">
                                        Baca Selengkapnya →
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada berita saat ini.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
