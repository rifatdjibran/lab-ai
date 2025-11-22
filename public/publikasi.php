<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Koneksi database PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Koneksi database gagal: " . pg_last_error());
}

date_default_timezone_set('Asia/Jakarta');

// Ambil data publikasi
$query = "SELECT id, judul, penulis, jurnal, tahun, link_publikasi, file_publikasi, created_at 
          FROM public.publikasi
          ORDER BY tahun DESC";
$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$dataPublikasi = pg_fetch_all($result);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Publikasi Ilmiah Laboratorium</h1>
        <p class="lead mt-1">Kumpulan publikasi ilmiah dari Laboratorium Applied Informatics</p>
    </div>
</section>

<!-- Publikasi List -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Daftar Publikasi</h2>

        <div class="row g-4">

            <?php if ($dataPublikasi): ?>
                <?php foreach ($dataPublikasi as $p): ?>
                    <div class="col-md-4">
                        <div class="card pub-card shadow-sm p-3">

                            <h5 class="fw-bold mb-1">
                                <?= htmlspecialchars($p['judul']) ?>
                            </h5>

                            <p class="mb-1 text-muted">
                                <strong>Penulis:</strong> <?= htmlspecialchars($p['penulis']) ?>
                            </p>

                            <p class="mb-1 text-muted">
                                <strong>Jurnal:</strong> <?= htmlspecialchars($p['jurnal']) ?>
                            </p>

                            <p class="mb-1 text-muted">
                                <strong>Tahun:</strong> <?= htmlspecialchars($p['tahun']) ?>
                            </p>

                            <!-- Link Publikasi -->
                            <?php if (!empty($p['link_publikasi'])): ?>
                                <p>
                                    <a href="<?= htmlspecialchars($p['link_publikasi']) ?>" 
                                       target="_blank" class="btn btn-primary btn-sm">
                                        <i class="bi bi-link-45deg"></i> Lihat Publikasi
                                    </a>
                                </p>
                            <?php endif; ?>

                            <!-- File Publikasi -->
                            <?php if (!empty($p['file_publikasi'])): ?>
                                <a href="../uploads/publikasi/<?= $p['file_publikasi'] ?>" 
                                   class="btn btn-success btn-sm" target="_blank">
                                   <i class="bi bi-file-earmark-pdf"></i> File PDF
                                </a>
                            <?php else: ?>
                                <small class="text-danger">Tidak ada file publikasi</small>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada publikasi.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

</body>
</html>
